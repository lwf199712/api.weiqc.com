<?php

namespace app\models\dataObject;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_physical_replace_order".
 * @package app\models\dataObject
 * @property int       $id
 * @property string    $nick_name          昵称
 * @property string    $we_chat_id         微信号
 * @property string    $fans_amount        粉丝量
 * @property string    $advert_location    广告位置
 * @property string    $put_times          投放次数
 * @property int       $dispatch_time      发文时间
 * @property string    $follower           跟进人
 * @property double    $female_powder_proportion 女粉占比
 * @property string    $put_link           投放链接
 * @property string    $replace_product    置换产品
 * @property string    $replace_quantity   置换件数
 * @property string    $brand              品牌
 * @property string    $average_reading    平均阅读量
 * @property string    $account_type       账号类型
 * @property int       $first_trial        初审（0/待审核1/已通过2/不通过）
 * @property int       $final_judgment     终审（0/待审核1/已通过2/不通过）
 * @property int       $prize_send_status  奖品寄出状态（0/未发货1/已发货）
 * @property string    $advert_read_num    广告阅读数
 * @property double    $volume_transaction 成交额
 * @property string    $new_fan_attention  新粉丝关注数
 * @property string    $first_audit_opinion 初审审核意见
 * @property string    $final_audit_opinion 终审审核意见
 * @property string    $first_auditor       初审人
 * @property string    $final_auditor       终审人
 */
class PhysicalReplaceOrderDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'bm_physical_replace_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['nick_name', 'we_chat_id'], 'required', 'message' => '{attribute}不能为空'],
            [['nick_name', 'we_chat_id', 'advert_location', 'follower',
                'replace_product', 'brand', 'account_type', 'first_audit_opinion', 'final_audit_opinion', 'first_auditor', 'final_auditor', 'fans_amount'], 'string'],

            [['id', 'dispatch_time', 'first_trial', 'final_judgment', 'prize_send_status', 'put_times', 'replace_quantity', 'average_reading', 'advert_read_num', 'new_fan_attention'], 'integer'],
            ['put_link', 'string', 'max' => 1024],
            [['female_powder_proportion', 'volume_transaction'], 'double'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                        => 'ID',
            'nick_name'                 => 'Nick Name',
            'we_chat_id'                => 'We Chat Id',
            'fans_amount'               => 'Fans Amount',
            'advert_location'           => 'Advert Location',
            'put_times'                 => 'Put Times',
            'dispatch_time'             => 'Dispatch Time',
            'follower'                  => 'Follower',
            'female_powder_proportion'  => 'Female Powder Proportion',
            'put_link'                  => 'Put Link',
            'replace_product'           => 'Replace Product',
            'replace_quantity'          => 'Replace Quantity',
            'brand'                     => 'Brand',
            'average_reading'           => 'Average Reading',
            'account_type'              => 'Account Type',
            'first_trial'               => 'First Trial',
            'final_judgment'            => 'Final Judgment',
            'prize_send_status'         => 'Prize Send Status',
            'advert_read_num'           => 'Advert Read Num',
            'volume_transaction'        => 'Volume Transaction',
            'new_fan_attention'         => 'New Fan Attention',
            'first_audit_opinion'       => 'First Audit Opinion',
            'final_audit_opinion'       => 'Final Audit Opinion',
            'first_auditor'             => 'First Auditor',
            'final_auditor'             => 'Final Auditor',
        ];
    }

}