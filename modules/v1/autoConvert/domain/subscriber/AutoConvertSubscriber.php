<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\domain\subscriber;

use app\common\infrastructure\dto\SingleMessageDto;
use app\models\dataObject\SectionRealtimeMsgDo;
use app\modules\v1\autoConvert\domain\event\AutoConvertEvent;
use app\modules\v1\autoConvert\enum\MessageEnum;
use app\modules\v1\autoConvert\enum\SectionRealtimeMsgEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Yii;
use yii\base\Exception;

/**
 * Class AutoConvertSubscriber
 * 1.初始化数据
 * 2.判断是否开启分配
 * 3.判断是否停止供粉
 * 4.判断是否达到供粉目标
 * 5.计算转粉公众号
 * 6.满粉处理
 *   ① 全体增量百分之十
 *   ② 判断是否满粉，不满粉则切换
 *   ③ 满粉则计算最高缺粉率部门
 * @version 1.0
 * @property bool $inTimeRange
 * @author  zhuozhen
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
            AutoConvertEvent::DEFAULT_SCENE   => [     //默认进粉事件
                ['init', -1],
                ['deptRule', -2],
                ['supportRule', -3],
                ['convertAim', -4],
                ['calculateDisparity', -5],
                ['raiseConvertAim', -6],
                ['fullFansConvertAim', -7],
                ['fullFansCalculateDisparity', -8],
            ],
            AutoConvertEvent::FULL_FANS_SCENE => [       //满粉循环
                ['raiseConvertAim', -1],
                ['fullFansConvertAim', -2],
                ['fullFansCalculateDisparity', -3],
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
        $event->setNodeInfo(['dept' => 1, 'method' => __FUNCTION__]);

        $redis             = $event->redisUtils->getRedis();
        $timeStamp         = $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE));
        $timeRange         = $event->autoConvertService->getTimeRange((int)$timeStamp);
        $nowTime           = time();
        $todayEndTime      = mktime(23, 59, 59, (int)date('m'), (int)date('d'), (int)date('Y'));
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
        $event->setNodeInfo(['dept' => 2, 'method' => __FUNCTION__]);

        if (empty($event->whiteList) && $event->distribute === 'no') {
            $event->setReturnDept();
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
        $event->setNodeInfo(['dept' => 3, 'method' => __FUNCTION__]);

        if ($event->stopSupport === 'yes') {
            $event->setReturnDept();
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
        $event->setNodeInfo(['dept' => 4, 'method' => __FUNCTION__]);

        $redis               = $event->redisUtils->getRedis();
        $diffVal             = $event->convertRequestInfo->fansCount - $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE));
        $thirtyMinFansTarget = $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department, SectionRealtimeMsgEnum::getThirtyMinFansTarget(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        if ($diffVal <= $thirtyMinFansTarget) {
            $event->setReturnDept();
            $event->stopPropagation();
        } else {
            //发送短信
            try {
                $currentDeptId            = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department, 'id');
                $currentDeptInfo          = $event->autoConvertSectionRealtimeMsgService->findOne(['id' => $currentDeptId]);
                $singleMessageDto         = new SingleMessageDto();
                $singleMessageDto->text   = '【供粉系统通知】您好，供粉超出目标量！当前30分钟' . $currentDeptInfo['currentDept'] . '分部的供粉量已经达到指定供粉量，请及时调整广告减少供粉量，避免分部粉丝供应过多，谢谢！';
                $singleMessageDto->mobile = $currentDeptInfo['control_member_phone'];
                $singleMessageDto->apikey = Yii::$app->params['api']['yunpian_api']['apikey'];
                $event->SMS->singleSendMsg($singleMessageDto);
                if ($currentDeptInfo['control_member_phone'] !== $currentDeptInfo['adminstrator_phone']) {
                    $singleMessageDto->phone = $currentDeptInfo['adminstrator_phone'];
                    $event->SMS->singleSendMsg($singleMessageDto);
                }
            } catch (Exception $exception) {
                Yii::info($exception->getMessage());
            } catch (GuzzleException $exception) {
                Yii::info($exception->getMessage());
            }
        }
    }

    /**
     * 计算缺粉率以选出需转入的分部
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function calculateDisparity(AutoConvertEvent $event): void
    {
        $lackRateAndDept = $event->autoConvertService->calculateLackFansRateService->calculateLackFansRate($event, false);
        $event->setNodeInfo(['dept' => 5, 'method' => __FUNCTION__, 'info' => $lackRateAndDept]);

        if ($lackRateAndDept === null) {
            $event->setReturnDept();
            $event->stopPropagation();
        }
        if ($lackRateAndDept['lackFansRate'] > 0) {
            //切换公众号
            $event->setReturnDept($lackRateAndDept['lackFansDept']);
            $event->stopPropagation();
        }
        //公众号都已满粉
    }

    /**
     * 全部满粉，增量百分之十
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function raiseConvertAim(AutoConvertEvent $event): void
    {
        $event->setNodeInfo(['dept' => 6, 'method' => __FUNCTION__]);

        //更新缓存中满粉目标数，在原有基础上增加10%
        $availableDept = SectionRealtimeMsgDo::find()->select('current_dept,thirty_min_fans_target')->asArray()->all();
        foreach ($availableDept as $v) {
            $oldFullFansCount = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $v['current_dept'], 'fullFansCount');
            if ($oldFullFansCount === null) {
                $event->redisUtils->getRedis()->hSet(MessageEnum::DC_REAL_TIME_MESSAGE . $v['current_dept'], 'fullFansCount', $v['thirty_min_fans_target']);
                $oldFullFansCount = $v['thirty_min_fans_target'];
            }
            $newFullFansCount = (int)$oldFullFansCount + ceil($v['thirty_min_fans_target'] * 0.1);
            Yii::info('oldFullFansCount:' . (int)$oldFullFansCount . ', thirty_min_fans_target:'.$v['thirty_min_fans_target']*0.1 );
            $event->redisUtils->getRedis()->hSet(MessageEnum::DC_REAL_TIME_MESSAGE . $v['current_dept'], 'fullFansCount', $newFullFansCount);
        }
    }

    /**
     * 计算增量后是否满粉
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function fullFansConvertAim(AutoConvertEvent $event): void
    {
        $event->setNodeInfo(['dept' => 7, 'method' => __FUNCTION__]);

        $redis   = $event->redisUtils->getRedis();
        $diffVal = $event->convertRequestInfo->fansCount - $redis->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestInfo->department, 'fullFansCount');
        if ($diffVal <= 0) {
            $event->setReturnDept($event->convertRequestInfo->department);
            $event->stopPropagation();
        }
    }

    /**
     * 满粉后计算最高缺粉率部门
     * @param AutoConvertEvent $event
     * @author zhuozhen
     */
    public function fullFansCalculateDisparity(AutoConvertEvent $event): void
    {

        $lackRateAndDept = $event->autoConvertService->calculateLackFansRateService->calculateLackFansRate($event, true);
        $event->setNodeInfo(['dept' => 8, 'method' => __FUNCTION__, 'info' => $lackRateAndDept]);


        if ($lackRateAndDept === null) {
            $event->setReturnDept();
            $event->stopPropagation();
        }
        if ($lackRateAndDept['lackFansRate'] > 0) {
            //切换公众号
            $event->setReturnDept($lackRateAndDept['lackFansDept']);
            $event->stopPropagation();
        } else {
            $autoConvertSubscriber = new self();
            $dispatcher            = new EventDispatcher();
            $dispatcher->addSubscriber($autoConvertSubscriber);
            $dispatcher->dispatch(AutoConvertEvent::FULL_FANS_SCENE, $event);
            $event->stopPropagation();
        }

    }


}