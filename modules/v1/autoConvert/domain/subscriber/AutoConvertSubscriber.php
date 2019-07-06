<?php
declare(strict_types=1);

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AutoConvertSubscriber
 * 1.初始化数据
 * 2.判断是否开启分配
 * 3.判断是否停止供粉
 * 4.判断是否达到供粉目标
 * 5.计算转粉公众号
 *
 * @version 1.0
 * @property bool $inTimeRange
 * @author zhuozhen
 */
class AutoConvertSubscriber implements EventSubscriberInterface
{

    /** @var bool $inTimeRange 是否在半小时时间范围内 */
    protected $inTimeRange;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AutoConvertEvent::NAME => [
                ['init', 1],
                ['deptRule', 2],
                ['supportRule', 3],
                ['convertAim', 4],
                ['calculateDisparity', 5],
            ],
        ];
    }

    /**
     * 初始化数据
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function init(AutoConvertEvent $event): void
    {
        $redis             = $event->redisUtils->getRedis();
        $timeStamp         = $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE));
        $timeRange         = $event->autoConvertService->getTimeRange($timeStamp);
        $nowTime           = time();
        $todayEndTime      = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        $this->inTimeRange = true;
        if ($timeRange['beginTime'] > $nowTime || $nowTime >= $timeRange['endTime']) {
            $currentThirtyMinInitVal = $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE));
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE), $currentThirtyMinInitVal);
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
            $this->inTimeRange = false;
        }
        $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE), time());
        $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE), $event->convertRequestInfo->fansCount);
        $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
        $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
    }


    /**
     * 判断是否开启分配（加上白名单过滤）
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function deptRule(AutoConvertEvent $event): void
    {
        if (empty($event->whiteList) && $event->distribute === 'no') {
            $event->setReturnDept(null);
            $event->stopPropagation();
        }
    }

    /**
     * 判断是否停止供粉
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function supportRule(AutoConvertEvent $event): void
    {
        if ($event->stopSupport === 'yes') {
            $event->setReturnDept(null);
            $event->stopPropagation();
        }
    }

    /**
     * 判断是否达到供粉目标
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function convertAim(AutoConvertEvent $event): void
    {
        $redis   = $event->redisUtils->getRedis();
        $diffVal = $event->convertRequestInfo->fansCount - $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE));
        $thirtyMinFansTarget = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE  . $event->convertRequestInfo->department, SectionRealtimeMsgEnum::getThirtyMinFansTarget(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        if ($diffVal <= $thirtyMinFansTarget){
            $event->setReturnDept(null);
            $event->stopPropagation();
        }
    }

    /**
     * 计算分部间的差距以选出需转入的分部
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function calculateDisparity(AutoConvertEvent $event): void
    {
    }
}