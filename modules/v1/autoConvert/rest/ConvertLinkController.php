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
        //Po service???
        $this->autoConvertSectionRealtimeMsgService = $autoConvertSectionRealtimeMsgService;
        $this->autoConvertStaticUrlService          = $autoConvertStaticUrlService;
        $this->autoConvertStaticConversionService   = $autoConvertStaticConversionService;
        //??????service???
        $this->calculateLackFansRateService = $calculateLackFansRateService;
        $this->autoConvertService           = $autoConvertService;
        $this->changeService                = $changeService;
        //?????????
        $this->requestUtils  = $requestUtils;
        $this->responseUtils = $responseUtils;
        $this->redisUtils    = $redisUtils;
        $this->dispatcher    = $dispatcher;
        //???????????????
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
     * ??????????????????
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
            return ['????????????',500,$autoConvertPrepareEvent->errors];
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

        //???????????????$autoConvertSubscriber????????????????????????$this->dispatcher???
        $this->dispatcher->addSubscriber($autoConvertSubscriber);
        $this->dispatcher->dispatch(AutoConvertEvent::DEFAULT_SCENE, $autoConvertEvent);
        $changeDept = $autoConvertEvent->getReturnDept();
        $restoreAllLinks = $autoConvertEvent->getRestoreAllLinks();

        if ($changeDept === null) {
            return ['????????????!????????????????????????', 200, [$changeDept, $autoConvertEvent->getNodeInfo()]];
        }

        //???????????????????????????
        if ($restoreAllLinks){
           $bool = $this->changeService->restoreAllLinks($convertRequestVo->department, $this->autoConvertStaticUrlService, $this->autoConvertStaticConversionService);
        }else{
            /**
             * ?????????????????????????????????????????????????????????
             * @var ChangeService __invoke
             */
            $bool = ($this->changeService)($convertRequestVo->department, $changeDept, $this->autoConvertStaticUrlService, $this->autoConvertStaticConversionService);
        }


        //????????????????????????????????????????????????????????????????????????
        $this->autoConvertService->sendMessageWhenArriveTodayFansCount($convertRequestVo, $this->SMS, $this->autoConvertSectionRealtimeMsgService);

        if ($bool){
            return ['????????????!???????????????', 200, [$changeDept, $autoConvertEvent->getNodeInfo()]];
        }

        return ['????????????!???????????????????????????????????????????????????',200];
    }
}