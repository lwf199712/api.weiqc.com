<?php
declare(strict_types=1);
namespace app\modules\v1\autoConvert\enum;

use app\components\core\BaseEnum;

/**
 * Class SectionRealtimeMsgEnum
 * SectionRealtimeMsg 类属性常量
 * @method static getCurrentDept(string $SECTION_REALTIME_MSG)
 * @method static getIsDistribute(string $SECTION_REALTIME_MSG)
 * @method static getIsStopSupportFans(string $SECTION_REALTIME_MSG)
 * @method static getWhiteList(string $SECTION_REALTIME_MSG)
 * @method static getThirtyMinFansTarget(string $SECTION_REALTIME_MSG)
 */
class SectionRealtimeMsgEnum extends BaseEnum
{
    /**
     * @currentDept('current_dept')
     * @currentDeptId('current_dept_id')
     * @todaySupportFans('today_support_fans')
     * @sixtyMinFansTarget('sixty_min_fans_target')
     * @thirtyMinFansTarget('thirty_min_fans_target')
     * @whiteList('white_list')
     * @controlMember('control_member')
     * @controlMemberPhone('control_member_phone')
     * @adminstrator('adminstrator')
     * @adminstratorPhone('adminstrator_phone')
     * @isDistribute('is_distribute')
     * @isAcceptDistribute('is_accept_distribute')
     * @isStopSupportFans('is_stop_support_fans')
     * @isDelete('is_delete')
     * @isMsgInform('is_msg_inform')
     * @monthTurnoverTarget('month_turnover_target')
     * @promoteProportionTarget('promote_proportion_target')
     */
    public const SECTION_REALTIME_MSG = 'section_realtime_msg';
}