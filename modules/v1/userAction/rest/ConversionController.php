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
use app\modules\v1\userAction\domain\po\StaticUrlPo;
use app\modules\v1\userAction\domain\vo\ConversionInfo;
use app\modules\v1\userAction\domain\vo\LinksInfo;
use app\modules\v1\userAction\service\impl\StaticConversionImpl;
use app\modules\v1\userAction\service\impl\StaticHitsImpl;
use app\modules\v1\userAction\service\impl\StaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\StaticUrlImpl;
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
 * @property StaticConversionService $staticConversion
 * @property StaticServiceConversionsImpl $staticServiceConversionsService
 * @property UserActionsAip $userActionsController
 * @property StaticHitsService $staticHitsService
 * @package app\modules\v1\rest
 * @author: lirong
 */
class ConversionController extends RestBaseController
{
    /* @var StaticUrlPo */
    public $modelClass = StaticUrlPo::class;
    /* @var ResponseUtils */
    public $responseUtils = ResponseUtils::class;
    /* @var StaticHitsService */
    public $staticHitsService = StaticHitsImpl::class;
    /* @var SourceDetectionUtil */
    private $sourceDetectionUtil = SourceDetectionUtil::class;
    /* @var IpLocationUtils */
    private $ipLocationUtils = IpLocationUtils::class;
    /* @var RequestUtils */
    private $requestUtils = RequestUtils::class;
    /* @var StaticUrlService */
    private $staticUrlService = StaticUrlImpl::class;
    /* @var StaticConversionService */
    private $staticConversion = StaticConversionImpl::class;
    /* @var StaticServiceConversionsService */
    private $staticServiceConversionsService = StaticServiceConversionsImpl::class;
    /* @var UserActionsAip */
    private $userActionsController = UserActionsAip::class;

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
            $conversionInfo = new ConversionInfo;
            $conversionInfo->setAttributes($this->request->post());
            $staticUrl = $this->staticUrlService::findOne(['ident' => $conversionInfo->token]);
            if (!$staticUrl) {
                return [false, 'Token不存在', 500];
            }
            if ($this->staticConversion::findOne([
                'ip'   => $this->responseUtils::ipToInt($this->request->getUserIP()),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id
            ])) {
                return [false, 'Ip已经被记录', 500];
            }
            //访问记录
            $staticConversionPo = new StaticConversionPo;
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
            $staticConversionId = $this->staticConversion::insert($staticConversionPo);
            //系统转化数增加
            $this->staticServiceConversionsService::increasedConversions($staticUrl);
            //广点通用户行为统计接口转化数增加
            $userActionsDto = new UserActionsDto;
            $userActionsDto->account_id = $this->request->post('account_id', -1);
            $userActionsDto->actions = new ActionsDto;
            $userActionsDto->actions->user_action_set_id = $this->request->post('user_action_set_id');
            $userActionsDto->actions->url = $this->request->post('url');
            $userActionsDto->actions->action_time = time();
            $userActionsDto->actions->action_type = ActionsDto::COMPLETE_ORDER;
            $userActionsDto->actions->trace = new TraceDto;
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
            $linksInfo = new LinksInfo;
            $linksInfo->setAttributes($this->request->post());
            $staticUrl = $this->staticUrlService::findOne(['ident' => $linksInfo->token]);
            if (!$staticUrl) {
                return [false, 'Token不存在', 500];
            }

            //检查客户端类型
            if ($staticUrl->pcurl && !$this->requestUtils::requestFromMobile()) {
                $pcurl = $staticUrl->pcurl;
            }
            $staticHitsService = $this->staticHitsService::findOne([
                'ip'   => long2ip($this->responseUtils::ipToInt($this->request->getUserIP())),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id,
            ]);


            return [true, '操作成功!', 200];
        } catch (ValidateException|Exception|TencentMarketingApiException $e) {
            return [false, $e->getMessage(), $e->getCode()];
        }
    }
}