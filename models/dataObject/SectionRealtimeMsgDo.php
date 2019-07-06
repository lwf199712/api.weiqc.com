<?php


namespace app\models\dataObject;

use yii\db\ActiveRecord;

/**
 * Class SectionRealtimeMsgDo
 * @property int    $id
 * @property string $current_dept
 * @property int    $current_dept_id
 * @property int    $today_support_fans
 * @property int    $sixty_min_fans_target
 * @property int    $thirty_min_fans_target
 * @property string $white_list
 * @property string $control_member
 * @property string $control_member_phone
 * @property string $adminstrator
 * @property string $adminstrator_phone
 * @property string $is_distribute
 * @property string $is_accept_distribute
 * @property string $is_stop_support_fans
 * @property int    $is_delete
 * @property string $is_msg_inform
 * @property int    $month_turnover_target
 * @property float  $promote_proportion_target
 * @package app\models\dataObject
 */
class SectionRealtimeMsgDo extends  ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%section_realtime_msg}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['current_dept', 'current_dept_id', 'today_support_fans', 'sixty_min_fans_target', 'control_member', 'control_member_phone', 'adminstrator', 'adminstrator_phone', 'is_distribute', 'is_accept_distribute', 'is_stop_support_fans', 'is_delete', 'is_msg_inform'], 'required'],
            [['current_dept', 'control_member', 'control_member_phone', 'adminstrator', 'adminstrator_phone', 'is_distribute', 'is_accept_distribute', 'is_stop_support_fans', 'is_delete', 'is_msg_inform'], 'string'],
            [['current_dept_id', 'today_support_fans', 'sixty_min_fans_target', 'thirty_min_fans_target'], 'integral'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                        => 'ID',
            'current_dept'              => '当前分部',
            'current_dept_id'           => '当前分部id',
            'today_support_fans'        => '今日总供粉数',
            'sixty_min_fans_target'     => '60分钟供粉目标',
            'thirty_min_fans_target'    => '30分钟供粉目标',
            'white_list'                => '白名单',
            'control_member'            => '调控人员',
            'control_member_phone'      => '调控人员电话',
            'adminstrator'              => '管理员',
            'adminstrator_phone'        => '管理人员电话',
            'is_distribute'             => '可分配-yes/开,no/关',
            'is_accept_distribute'      => '接受分配-yes/开,no/关',
            'is_stop_support_fans'      => '停止供粉-yes/开,no/关',
            'is_delete'                 => '0/未删除  1/删除',
            'is_msg_inform'             => '短信通知-yes/开,no/关',
            'month_turnover_target'     => '月营业额目标',
            'promote_proportion_target' => '推广占比目标',
        ];
    }
}