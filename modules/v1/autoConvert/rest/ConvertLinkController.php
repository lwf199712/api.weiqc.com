<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\rest;

use app\common\infrastructure\service\SMS;
use app\common\rest\RestBaseController;
use app\common\utils\RedisUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use app\modules\v1\autoConvert\domain\event\AutoConvertEvent;
use app\modules\v1\autoConvert\domain\subscriber\AutoConvertSubscriber;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\enum\MessageEnum;
use app\modules\v1\autoConvert\enum\SectionRealtimeMsgEnum;
use app\modules\v1\autoConvert\service\AutoConvertSectionRealtimeMsgService;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\service\AutoConvertStaticConversionService;
use app\modules\v1\autoConvert\service\AutoConvertStaticUrlService;
use app\modules\v1\autoConvert\service\CalculateLackFansRateService;
use app\modules\v1\autoConvert\service\ChangeService;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @property AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService
 * @property AutoConvertStaticUrlService          $autoConvertStaticUrlService
 * @property AutoConvertStaticConversionService   $autoConvertStaticConversionService
 * @property CalculateLackFansRateService         $calculateLackFansRateService
 * @property AutoConvertService                   $autoConvertService
 * @property ChangeService                        $changeService
 * @property RequestUtils                         $requestUtils
 * @property ResponseUtils                        $responseUtils
 * @property RedisUtils                           $redisUtils
 * @property EventDispatcher                      dispatcher
 * @property string                               $distribute
 * @property string                               $stopSupport
 * @property string                               $whiteList
 * @property SMS                                  $SMS
 * Class ConvertLinkController
 */
class ConvertLinkController extends RestBaseController
{
    /** @var AutoConvertStaticUrlService */
    protected $autoConvertStaticUrlService;
    /** @var AutoConvertStaticConversionService */
    protected $autoConvertStaticConversionService;
    /** @var CalculateLackFansRateService */
    protected $calculateLackFansRateService;
    /** @var  AutoConvertService */
    protected $autoConvertService;
    /** @var ChangeService */
    protected $changeService;
    /** @var AutoConvertSectionRealtimeMsgService */
    protected $autoConvertSectionRealtimeMsgService;
    /* @var RequestUtils */
    protected $requestUtils;
    /* @var ResponseUtils */
    protected $responseUtils;
    /** @var  RedisUtils */
    protected $redisUtils;
    /** @var EventDispatcher */
    protected $dispatcher;
    /** @var SMS */
    protected $SMS;
    /** @var string $distribute 是否可分配 */
    protected $distribute;
    /** @var string $stopSupport 是否停止供粉 */
    protected $stopSupport;
    /** @var string $whiteList 白名单 */
    protected $whiteList;


    public function __construct($id, $module,
                                AutoConvertStaticUrlService $autoConvertStaticUrlService,
                                AutoConvertStaticConversionService $autoConvertStaticConversionService,
                                AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService,
                                CalculateLackFansRateService $calculateLackFansRateService,
                                AutoConvertService $autoConvertService,
                                ChangeService $changeService,
                                RequestUtils $requestUtils,
                                ResponseUtils $responseUtils,
                                EventDispatcher $dispatcher,
                                RedisUtils $redisUtils,
                                SMS $SMS,
                                $config = [])
    {
        //Po service类
        $this->autoConvertSectionRealtimeMsgService = $autoConvertSectionRealtimeMsgService;
        $this->autoConvertStaticUrlService          = $autoConvertStaticUrlService;
        $this->autoConvertStaticConversionService   = $autoConvertStaticConversionService;
        //业务service类
        $this->calculateLackFansRateService = $calculateLackFansRateService;
        $this->autoConvertService           = $autoConvertService;
        $this->changeService                = $changeService;
        //工具类
        $this->requestUtils  = $requestUtils;
        $this->responseUtils = $responseUtils;
        $this->redisUtils    = $redisUtils;
        $this->dispatcher    = $dispatcher;
        //基础设施类
        $this->SMS = $SMS;
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
            'convert' => ['GET', 'HEAD'],
        ];
    }

    /**
     * 自动转粉接口
     * @return array
     * @author zhuozhen
     */
    public function actionConvert(): array
    {
        $convertRequestInfo = new ConvertRequestVo();
        $convertRequestInfo->setAttributes($this->request->get());
        $deptIsExists = $this->autoConvertService->checkDeptExists($convertRequestInfo);
        if ($deptIsExists === false) {
            return ['操作失败！当前公众号不存在', 406];
        }
        $this->autoConvertService->prepareData($convertRequestInfo);
        $this->autoConvertService->initDept($convertRequestInfo);
        $redis = $this->redisUtils->getRedis();
        //是否可分配
        $this->distribute = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getIsDistribute(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        //是否停止供粉
        $this->stopSupport = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getIsStopSupportFans(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        //白名单
        $this->whiteList = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getWhiteList(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));

        $autoConvertSubscriber = new AutoConvertSubscriber();
        $autoConvertEvent      = new AutoConvertEvent($convertRequestInfo,
                                                    $this->autoConvertService,
                                                    $this->autoConvertSectionRealtimeMsgService,
                                                    $this->SMS,
                                                    $this->redisUtils,
                                                    $this->distribute,
                                                    $this->stopSupport,
                                                    $this->whiteList,
                                      AutoConvertEvent::FIRST_IN_FULL_FANS);
        $this->dispatcher->addSubscriber($autoConvertSubscriber);
        $this->dispatcher->dispatch(AutoConvertEvent::DEFAULT_SCENE, $autoConvertEvent);
        $changeDept = $autoConvertEvent->getReturnDept();

        if ($changeDept === null) {
            return ['操作成功!暂时没有转换链接', 200, [$changeDept, $autoConvertEvent->getNodeInfo()]];
        }
        /** @var ChangeService __invoke */
        ($this->changeService)($convertRequestInfo->department, $changeDept, $this->autoConvertStaticUrlService, $this->autoConvertStaticConversionService);
        //当今日进粉数达到设置的今日供粉数，则发送一条消息
        $this->autoConvertService->sendMessageWhenArriveTodayFansCount($convertRequestInfo, $this->SMS,$this->autoConvertSectionRealtimeMsgService);
        return ['操作成功!已转换链接', 200, [$changeDept, $autoConvertEvent->getNodeInfo()]];

    }


}