<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\rest;

use app\common\infrastructure\service\SMS;
use app\common\rest\RestBaseController;
use app\common\utils\RedisUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use app\modules\v1\autoConvert\domain\behavior\AutoConvertBehavior;
use app\modules\v1\autoConvert\domain\event\AutoConvertEvent;
use app\modules\v1\autoConvert\domain\event\AutoConvertPrepareEvent;
use app\modules\v1\autoConvert\domain\subscriber\AutoConvertSubscriber;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\service\AutoConvertSectionRealtimeMsgService;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\service\AutoConvertStaticConversionService;
use app\modules\v1\autoConvert\service\AutoConvertStaticUrlService;
use app\modules\v1\autoConvert\service\CalculateLackFansRateService;
use app\modules\v1\autoConvert\service\ChangeService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Yii;
use yii\base\InvalidConfigException;

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

    /**
     * ConvertLinkController constructor.
     * @param                                      $id
     * @param                                      $module
     * @param AutoConvertStaticUrlService          $autoConvertStaticUrlService
     * @param AutoConvertStaticConversionService   $autoConvertStaticConversionService
     * @param AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService
     * @param CalculateLackFansRateService         $calculateLackFansRateService
     * @param AutoConvertService                   $autoConvertService
     * @param ChangeService                        $changeService
     * @param RequestUtils                         $requestUtils
     * @param ResponseUtils                        $responseUtils
     * @param EventDispatcher                      $dispatcher
     * @param RedisUtils                           $redisUtils
     * @param SMS                                  $SMS
     * @param array                                $config
     * @throws InvalidConfigException
     */
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

        $this->on(AutoConvertPrepareEvent::class, Yii::createObject(['class' => AutoConvertBehavior::class]));
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
     * @throws InvalidConfigException
     * @author zhuozhen
     */
    public function actionConvert(): array
    {
        $convertRequestVo = new ConvertRequestVo();
        $convertRequestVo->setAttributes($this->request->get());
        $this->trigger(AutoConvertPrepareEvent::class, $autoConvertPrepareEvent = Yii::createObject(['class' => AutoConvertPrepareEvent::class], [
            $convertRequestVo, $this->autoConvertService, $this->redisUtils,
        ]));

        if ($autoConvertPrepareEvent->errors !== null) {
            return ['操作失败',500,$autoConvertPrepareEvent->errors];
        }
        $autoConvertSubscriber = new AutoConvertSubscriber;
        $autoConvertEvent      = new AutoConvertEvent($convertRequestVo,
            $this->autoConvertService,
            $this->autoConvertSectionRealtimeMsgService,
            $this->SMS,
            $this->redisUtils,
            $autoConvertPrepareEvent->distribute,
            $autoConvertPrepareEvent->stopSupport,
            $autoConvertPrepareEvent->whiteList,
            AutoConvertEvent::FIRST_IN_FULL_FANS);

        //把订阅器（$autoConvertSubscriber）注册给派遣器（$this->dispatcher）
        $this->dispatcher->addSubscriber($autoConvertSubscriber);
        $this->dispatcher->dispatch(AutoConvertEvent::DEFAULT_SCENE, $autoConvertEvent);
        $changeDept = $autoConvertEvent->getReturnDept();
        $restoreAllLinks = $autoConvertEvent->getRestoreAllLinks();

        if ($changeDept === null) {
            return ['操作成功!暂时没有转换链接', 200, [$changeDept, $autoConvertEvent->getNodeInfo()]];
        }

        //是否要还原所有链接
        if ($restoreAllLinks){
           $bool = $this->changeService->restoreAllLinks($convertRequestVo->department, $this->autoConvertStaticUrlService, $this->autoConvertStaticConversionService);
        }else{
            /**
             * 修改统计链接的公众号及当前公众号字段值
             * @var ChangeService __invoke
             */
            $bool = ($this->changeService)($convertRequestVo->department, $changeDept, $this->autoConvertStaticUrlService, $this->autoConvertStaticConversionService);
        }


        //当今日进粉数达到设置的今日供粉数，则发送一条消息
        $this->autoConvertService->sendMessageWhenArriveTodayFansCount($convertRequestVo, $this->SMS, $this->autoConvertSectionRealtimeMsgService);

        if ($bool){
            return ['操作成功!已转换链接', 200, [$changeDept, $autoConvertEvent->getNodeInfo()]];
        }

        return ['操作成功!查询不到转化的链接，无链接被转化。',200];
    }
}