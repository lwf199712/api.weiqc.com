<?php
declare(strict_types = 1);

use app\common\rest\RestBaseController;
use app\common\utils\RedisUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @property RequestUtils  $requestUtils
 * @property ResponseUtils $responseUtils
 * @property RedisUtils    $redisUtils
 * @property EventDispatcher dispatcher
 * @property string $distribute
 * @property string $stopSupport
 * @property string $whiteList
 * Class ConvertLinkController
 */
class ConvertLinkController extends RestBaseController
{

    /* @var RequestUtils */
    protected $requestUtils;
    /* @var ResponseUtils */
    protected $responseUtils;
    /** @var  RedisUtils */
    protected $redisUtils;
    /** @var EventDispatcher  */
    protected $dispatcher;
    /** @var string $distribute 是否可分配 */
    protected $distribute;
    /** @var string $stopSupport 是否停止供粉 */
    protected $stopSupport;
    /** @var string $whiteList 白名单 */
    protected $whiteList;



    public function __construct($id, $module,
                                RequestUtils $requestUtils,
                                ResponseUtils $responseUtils,
                                EventDispatcher $dispatcher,
                                RedisUtils $redisUtils,
                                $config = [])
    {
        //工具类
        $this->requestUtils  = $requestUtils;
        $this->responseUtils = $responseUtils;
        $this->redisUtils    = $redisUtils;
        $this->dispatcher    = $dispatcher;
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

    public function convert() : void
    {
        $convertRequestInfo = new ConvertRequestVo();
        $convertRequestInfo->setAttributes($this->request->get());
        $autoConvertService  = new AutoConvertService($this->redisUtils,$convertRequestInfo);
        $autoConvertService->prepareData();
        $autoConvertService->initDept();
        $redis  = $this->redisUtils->getRedis();
        //是否可分配
        $this->distribute = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getIsDistribute(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        //是否停止供粉
        $this->stopSupport = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getIsStopSupportFans(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        //白名单
        $this->whiteList = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getWhiteList(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));

        $autoConvertSubscriber = new AutoConvertSubscriber();
        $autoConvertEvent = new AutoConvertEvent($convertRequestInfo,$autoConvertService,$this->redisUtils,$this->distribute,$this->stopSupport,$this->whiteList);
        $this->dispatcher->addSubscriber($autoConvertSubscriber);
        $this->dispatcher->dispatch(AutoConvertEvent::NAME,$autoConvertEvent);
        $changeDept = $autoConvertEvent->getReturnDept();
        if ($changeDept === null){
            return ;
        }
        $autoConvertService->changeService();
    }


}