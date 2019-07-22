<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\domain\behavior;

use app\modules\v1\autoConvert\domain\event\AutoConvertPrepareEvent;
use app\modules\v1\autoConvert\enum\MessageEnum;
use app\modules\v1\autoConvert\enum\SectionRealtimeMsgEnum;
use yii\base\Behavior;

/**
 * Class AutoConvertBehavior
 * @package app\modules\v1\autoConvert\domain\behavior
 */
class AutoConvertBehavior extends Behavior
{

    public function __invoke(AutoConvertPrepareEvent $event)
    {
        $deptIsExists = $event->autoConvertService->checkDeptExists($event->convertRequestVo);
        if ($deptIsExists === false) {
            $event->errors = ['操作失败！当前公众号不存在', 406];
            return;
        }
        $event->autoConvertService->prepareData($event->convertRequestVo);
        $event->autoConvertService->initDept($event->convertRequestVo);
        $event->distribute  = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestVo->department, SectionRealtimeMsgEnum::getIsDistribute(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        $event->stopSupport = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestVo->department, SectionRealtimeMsgEnum::getIsStopSupportFans(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        $event->whiteList   = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $event->convertRequestVo->department, SectionRealtimeMsgEnum::getWhiteList(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));

    }

}