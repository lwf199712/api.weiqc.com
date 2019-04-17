<?php

namespace app\modules\v1\userAction\rest;

use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingApi\userActions\enum\ActionTypeEnum;
use app\common\exception\RedisException;
use app\common\rest\RestBaseController;
use app\common\exception\TencentMarketingApiException;
use app\common\exception\ValidateException;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;
use app\models\dataObject\StaticConversionDo;
use app\modules\v1\userAction\domain\vo\ConversionInfo;
use app\modules\v1\userAction\enum\ConversionEnum;
use app\modules\v1\userAction\service\UserActionStaticConversionService;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use app\modules\v1\userAction\service\UserActionStaticServiceConversionsService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use app\common\utils\IpLocationUtils;
use app\common\utils\RedisUtils;
use app\common\utils\ResponseUtils;
use app\common\utils\SourceDetectionUtil;
use app\common\utils\RequestUtils;
use Exception;

/**
 * Landing page conversions（copy WeChat）.
 * Class ConversionController
 *
 * @property UserActionStaticUrlService $staticUrlService
 * @property UserActionStaticConversionService $staticConversionService
 * @property UserActionStaticServiceConversionsService $staticServiceConversionsService
 * @property UserActionStaticHitsService $staticHitsService
 * @property UserActionsApi $userActionsApi
 * @property ResponseUtils $responseUtils
 * @property SourceDetectionUtil $sourceDetectionUtil
 * @property IpLocationUtils $ipLocationUtils
 * @property RequestUtils $requestUtils
 * @property RedisUtils $redisUtils
 *
 * @package app\modules\v1\rest
 * @author: lirong
 */
class ConversionController extends RestBaseController
{
    /* @var UserActionStaticHitsService */
    protected $staticHitsService;
    /* @var UserActionStaticUrlService */
    protected $staticUrlService;
    /* @var UserActionStaticConversionService */
    protected $staticConversionService;
    /* @var UserActionStaticServiceConversionsService */
    protected $staticServiceConversionsService;
    /* @var ResponseUtils */
    protected $responseUtils;
    /* @var SourceDetectionUtil */
    protected $sourceDetectionUtil;
    /* @var IpLocationUtils */
    protected $ipLocationUtils;
    /* @var RequestUtils */
    protected $requestUtils;
    /* @var RedisUtils */
    protected $redisUtils;
    /* @var UserActionsApi */
    protected $userActionsApi;

    /**
     * ConversionController constructor.
     *
     * @param $id
     * @param $module
     * @param UserActionStaticHitsService $staticHitsService
     * @param UserActionStaticUrlService $staticUrlService
     * @param UserActionStaticConversionService $staticConversionService
     * @param UserActionStaticServiceConversionsService $staticServiceConversionsService
     * @param SourceDetectionUtil $sourceDetectionUtil
     * @param ResponseUtils $responseUtils
     * @param IpLocationUtils $ipLocationUtils
     * @param RequestUtils $requestUtils
     * @param RedisUtils $redisUtils
     * @param UserActionsApi $userActionsApi
     * @param array $config
     */
    public function __construct($id, $module,
                                UserActionStaticHitsService $staticHitsService,
                                UserActionStaticUrlService $staticUrlService,
                                UserActionStaticConversionService $staticConversionService,
                                UserActionStaticServiceConversionsService $staticServiceConversionsService,
                                SourceDetectionUtil $sourceDetectionUtil,
                                ResponseUtils $responseUtils,
                                IpLocationUtils $ipLocationUtils,
                                RequestUtils $requestUtils,
                                RedisUtils $redisUtils,
                                UserActionsApi $userActionsApi,
                                $config = [])
    {
        $this->staticHitsService = $staticHitsService;
        $this->staticUrlService = $staticUrlService;
        $this->staticConversionService = $staticConversionService;
        $this->staticServiceConversionsService = $staticServiceConversionsService;
        $this->userActionsApi = $userActionsApi;
        //工具类
        $this->responseUtils = $responseUtils;
        $this->sourceDetectionUtil = $sourceDetectionUtil;
        $this->ipLocationUtils = $ipLocationUtils;
        $this->requestUtils = $requestUtils;
        $this->responseUtils = $responseUtils;
        $this->redisUtils = $redisUtils;
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
        return [
            'add-conversion' => ['POST', 'HEAD'],
            'add-view'       => ['POST', 'HEAD']
        ];
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
            $this->sourceDetectionUtil->crossDomainDetection();
            $conversionInfo = new ConversionInfo();
            $conversionInfo->setAttributes($this->request->post());
            //检查落地页是否存在
            $staticUrl = $this->staticUrlService->findOne(['ident' => $conversionInfo->token]);
            if (!$staticUrl) {
                return [false, 'Token不存在', 500];
            }
            if ($this->staticConversionService->exists([
                'ip'   => $this->responseUtils->ipToInt($this->request->getUserIP()),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id
            ])) {
                return [false, 'Ip已经被记录', 500];
            }
            //访问记录
            $staticConversionPo = new StaticConversionDo();
            $staticConversionPo->wxh = $conversionInfo->wxh;
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
                $staticConversionPo->referer = $_SERVER['HTTP_REFERER'];
            }
            $staticConversionPo->agent = $_SERVER['HTTP_USER_AGENT'];
            $staticConversionPo->createtime = $_SERVER['REQUEST_TIME'];
            $staticConversionPo->ip = $this->responseUtils->ipToInt($this->request->getUserIP());
            $ipLocationUtils = $this->ipLocationUtils->getlocation(long2ip($staticConversionPo->ip));
            $staticConversionPo->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $staticConversionPo->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $staticConversionPo->date = strtotime(date('Y-m-d'));
            $staticConversionPo->page = $staticUrl->url;
            if ($staticUrl->pcurl && !$this->requestUtils->requestFromMobile()) {
                $staticConversionPo->page = $staticUrl->pcurl;
            }
            $staticConversionPo->u_id = $staticUrl->id;
            $staticConversionId = $this->staticConversionService->insert($staticConversionPo);
            //系统转化数增加
            $this->staticServiceConversionsService->increasedConversions($staticUrl);
            //广点通用户行为统计接口转化数增加
            $userActionsDto = new UserActionsDto();
            $userActionsDto->account_id = $this->request->post('account_id', -1);
            $userActionsDto->actions = new ActionsDto();
            $userActionsDto->actions->user_action_set_id = $this->request->post('user_action_set_id');
            $userActionsDto->actions->url = $this->request->post('url');
            $userActionsDto->actions->action_time = time();
            $userActionsDto->actions->action_type = ActionTypeEnum::COMPLETE_ORDER;
            $userActionsDto->actions->trace = new TraceDto();
            $userActionsDto->actions->trace->click_id = $this->request->post('click_id', -1);
            if ($this->request->post('action_param')) {
                $userActionsDto->actions->action_param = $this->request->post('action_param');
            }
            $userActionsDto->actions->outer_action_id = $staticConversionId;
            $userActionsDto->actions = [$userActionsDto->actions];
            $this->userActionsApi->add($userActionsDto);
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
            //点击数(存储在redis)
            $redisAddViewDto = new RedisAddViewDto();
            $redisAddViewDto->token = $this->request->post('token');
            $redisAddViewDto->referer = '';
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
                $redisAddViewDto->referer = $_SERVER['HTTP_REFERER'];
            }
            $redisAddViewDto->ip = long2ip($this->responseUtils->ipToInt($this->request->getUserIP()));
            $redisAddViewDto->agent = addslashes($_SERVER['HTTP_USER_AGENT']);
            $redisAddViewDto->createtime = $_SERVER['REQUEST_TIME'];
            $ipLocationUtils = $this->ipLocationUtils->getlocation(long2ip($this->responseUtils->ipToInt($this->request->getUserIP())));
            $redisAddViewDto->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $redisAddViewDto->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $redisAddViewDto->url = $this->request->post('url');
            $redisAddViewDto->date = strtotime(date('Y-m-d'));
            $redisAddViewDto->account_id = $this->request->post('account_id', -1);
            $redisAddViewDto->user_action_set_id = $this->request->post('user_action_set_id');
            $redisAddViewDto->click_id = $this->request->post('click_id', -1);
            $redisAddViewDto->action_param = $this->request->post('action_param');
            $redisAddViewDto->request_from_mobile = $this->requestUtils->requestFromMobile();
            //redis存储
            if (!$this->redisUtils->getRedis()->rpush(ConversionEnum::REDIS_ADD_VIEW, [json_encode($redisAddViewDto->attributes)])) {
                throw new RedisException('push list false', 500);
            }
            return [true, '操作成功!', 200];
        } catch (Exception|RedisException $e) {
            return [false, $e->getMessage(), $e->getCode()];
        }
    }

    /**
     * transaction close
     *
     * @return array
     * @author: lirong
     */
    protected function transactionClose(): array
    {
        return ['actionAddViews'];
    }
}