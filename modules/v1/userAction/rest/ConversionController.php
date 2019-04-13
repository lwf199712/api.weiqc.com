<?php

namespace app\modules\v1\userAction\rest;

use app\api\tencentMarketingApi\userActions\api\UserActionsAip;
use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\common\rest\RestBaseController;
use app\common\exception\TencentMarketingApiException;
use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticConversionPo;
use app\modules\v1\userAction\domain\po\StaticHitsPo;
use app\modules\v1\userAction\domain\vo\ConversionInfo;
use app\modules\v1\userAction\domain\vo\LinksInfo;
use app\modules\v1\userAction\service\StaticConversionService;
use app\modules\v1\userAction\service\StaticHitsService;
use app\modules\v1\userAction\service\StaticServiceConversionsService;
use app\modules\v1\userAction\service\StaticUrlService;
use app\utils\IpLocationUtils;
use app\utils\ResponseUtils;
use app\utils\SourceDetectionUtil;
use app\utils\RequestUtils;
use Exception;

/**
 * Landing page conversions（copy WeChat）.
 * Class ConversionController
 *
 * @property SourceDetectionUtil $sourceDetectionUtil
 * @property IpLocationUtils $ipLocationUtils
 * @property RequestUtils $requestUtils
 * @property StaticUrlService $staticUrlService
 * @property StaticConversionService $staticConversionService
 * @property StaticServiceConversionsService $staticServiceConversionsService
 * @property UserActionsAip $userActionsController
 * @property StaticHitsService $staticHitsService
 * @package app\modules\v1\rest
 * @author: lirong
 */
class ConversionController extends RestBaseController
{
    /* @var StaticHitsService */
    protected $staticHitsService;
    /* @var StaticUrlService */
    protected $staticUrlService;
    /* @var StaticConversionService */
    protected $staticConversionService;
    /* @var StaticServiceConversionsService */
    protected $staticServiceConversionsService;
    /* @var ResponseUtils */
    protected $responseUtils = ResponseUtils::class;
    /* @var SourceDetectionUtil */
    protected $sourceDetectionUtil = SourceDetectionUtil::class;
    /* @var IpLocationUtils */
    protected $ipLocationUtils = IpLocationUtils::class;
    /* @var RequestUtils */
    protected $requestUtils = RequestUtils::class;
    /* @var UserActionsAip */
    protected $userActionsController = UserActionsAip::class;

    public function __construct($id, $module,
                                StaticUrlService $staticHitsService,
                                StaticUrlService $staticUrlService,
                                StaticConversionService $staticConversionService,
                                StaticServiceConversionsService $staticServiceConversionsService,
                                $config = [])
    {
        $this->staticHitsService = $staticHitsService;
        $this->staticUrlService = $staticUrlService;
        $this->staticConversionService = $staticConversionService;
        $this->staticServiceConversionsService = $staticServiceConversionsService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Declares the allowed HTTP verbs.
     *
     * @return array
     * @author: lirong
     */
    public function verbs(): array
    {
        return ['add-conversion' => ['POST', 'HEAD']];
    }

    /**
     * Landing page conversions - add conversions
     * action_type COMPLETE_ORDER
     * complete order
     *
     * @author: lirong
     */
    public function actionAddConversion(): array
    {
        try {
            $this->sourceDetectionUtil::crossDomainDetection();
            $conversionInfo = new ConversionInfo();
            $conversionInfo->setAttributes($this->request->post());
            //检查落地页是否存在
            $staticUrl = $this->staticUrlService::findOne(['ident' => $conversionInfo->token]);
            if (!$staticUrl) {
                return [false, 'Token不存在', 500];
            }
            if ($this->staticConversionService->findOne([
                'ip'   => $this->responseUtils::ipToInt($this->request->getUserIP()),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id
            ])) {
                return [false, 'Ip已经被记录', 500];
            }
            //访问记录
            $staticConversionPo = new StaticConversionPo();
            $staticConversionPo->wxh = $conversionInfo->wxh;
            $staticConversionPo->referer = $_SERVER['HTTP_REFERER'] ?? '';
            $staticConversionPo->agent = $_SERVER['HTTP_USER_AGENT'];
            $staticConversionPo->createtime = $_SERVER['REQUEST_TIME'];
            /* @var $ipLocationUtils IpLocationUtils */
            $ipLocationUtils = $this->ipLocationUtils::getIpLocationUtils();
            $ipLocationUtils = $ipLocationUtils->getlocation(long2ip($this->responseUtils::ipToInt($this->request->getUserIP())));
            $staticConversionPo->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $staticConversionPo->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $staticConversionPo->date = strtotime(date('Y-m-d'));
            $staticConversionPo->page = $staticUrl->url;
            if ($staticUrl->pcurl && !$this->requestUtils::requestFromMobile()) {
                $staticConversionPo->page = $staticUrl->pcurl;
            }
            $staticConversionPo->ip = $this->responseUtils::ipToInt($this->request->getUserIP());
            $staticConversionPo->u_id = $staticUrl->id;
            $staticConversionId = $this->staticConversionService->insert($staticConversionPo);
            //系统转化数增加
            $this->staticServiceConversionsService::increasedConversions($staticUrl);
            //广点通用户行为统计接口转化数增加
            $userActionsDto = new UserActionsDto();
            $userActionsDto->account_id = $this->request->post('account_id', -1);
            $userActionsDto->actions = new ActionsDto();
            $userActionsDto->actions->user_action_set_id = $this->request->post('user_action_set_id');
            $userActionsDto->actions->url = $this->request->post('url');
            $userActionsDto->actions->action_time = time();
            $userActionsDto->actions->action_type = ActionsDto::COMPLETE_ORDER;
            $userActionsDto->actions->trace = new TraceDto();
            $userActionsDto->actions->trace->click_id = $this->request->post('click_id', -1);
            if ($this->request->post('action_param')) {
                $userActionsDto->actions->action_param = $this->request->post('action_param');
            }
            $userActionsDto->actions->outer_action_id = $staticConversionId;
            $userActionsDto->actions = [$userActionsDto->actions];
            /* @var $userActionsAip UserActionsAip */
            $userActionsAip = new $this->userActionsController;
            $userActionsAip->add($userActionsDto);
            return [true, '操作成功!', 200];
        } catch (ValidateException|Exception|TencentMarketingApiException $e) {
            return [false, $e->getMessage(), $e->getCode()];
        }
    }

    /**
     *
     * Landing page conversions - add views
     * action_type VIEW_CONTENT
     *
     * @return array
     * @author: lirong
     */
    public function actionAddViews(): array
    {
        try {
            $linksInfo = new LinksInfo();
            $linksInfo->setAttributes($this->request->post());
            //检查落地页是否存在
            $staticUrl = $this->staticUrlService::findOne(['ident' => $linksInfo->token]);
            if (!$staticUrl) {
                return [false, 'Token不存在', 500];
            }
            //检查客户端类型
            $page = $staticUrl->url;
            if ($staticUrl->pcurl && !$this->requestUtils::requestFromMobile()) {
                $page = $staticUrl->pcurl;
            }
            //检查点击数是否存在
            if ($this->staticHitsService::exists([
                'ip'   => long2ip($this->responseUtils::ipToInt($this->request->getUserIP())),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id,
            ])) {
                return [false, 'IP点击数已存在!'];
            }
            //点击数
            $staticHitsPo = new StaticHitsPo();
            $staticHitsPo->u_id = $staticUrl->id;
            $staticHitsPo->referer = $_SERVER['HTTP_REFERER'];
            $staticHitsPo->ip = long2ip($this->responseUtils::ipToInt($this->request->getUserIP()));
            $staticHitsPo->agent = addslashes($_SERVER['HTTP_USER_AGENT']);
            $staticHitsPo->createtime = $_SERVER['REQUEST_TIME'];
            /* @var $ipLocationUtils IpLocationUtils */
            $ipLocationUtils = $this->ipLocationUtils::getIpLocationUtils();
            $ipLocationUtils = $ipLocationUtils->getlocation(long2ip($this->responseUtils::ipToInt($this->request->getUserIP())));
            $staticHitsPo->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $staticHitsPo->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $staticHitsPo->date = strtotime(date('Y-m-d'));
            $staticHitsPo->page = $page;
            //TODO redis暂存
            return [true, '操作成功!', 200];
        } catch (Exception $e) {
            return [false, $e->getMessage(), $e->getCode()];
        }
    }
}