<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_tiktok_resource_base_cooperate".
 *
 * @property int $id
 * @property int $resource_base_id 资源库ID
 * @property string $channel 渠道
 * @property string $kol_name kol名称
 * @property string $account_id 账号ID
 * @property string $talent_introduction 达人简介
 * @property string $account_type 账号类型
 * @property string $account_link 账号链接
 * @property int $fans_num 粉丝量(W)
 * @property int $quotation 合作价格/报价(W)
 * @property int $cooperate_video_num 已合作视频数
 * @property int $cooperate_fee 已合作费用（W）
 * @property string $cooperate_info 合作情况
 * @property string $contact 联系人
 * @property string $contact_info 联系方式
 * @property int $updated_at 更新时间
 * @property string $follow 跟进人
 */
class TikTokResourceBaseCooperateDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tiktok_resource_base_cooperate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resource_base_id', 'kol_name', 'account_id'], 'required'],
            [['resource_base_id', 'fans_num', 'quotation', 'cooperate_video_num', 'cooperate_fee', 'updated_at'], 'integer'],
            [['talent_introduction'], 'string'],
            [['channel', 'kol_name', 'account_id', 'account_type', 'account_link', 'cooperate_info', 'contact', 'contact_info', 'follow'], 'string', 'max' => 255],
            [['account_id', 'kol_name'], 'unique', 'targetAttribute' => ['account_id', 'kol_name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resource_base_id' => 'Resource Base ID',
            'channel' => 'Channel',
            'kol_name' => 'Kol Name',
            'account_id' => 'Account ID',
            'talent_introduction' => 'Talent Introduction',
            'account_type' => 'Account Type',
            'account_link' => 'Account Link',
            'fans_num' => 'Fans Num',
            'quotation' => 'Quotation',
            'cooperate_video_num' => 'Cooperate Video Num',
            'cooperate_fee' => 'Cooperate Fee',
            'cooperate_info' => 'Cooperate Info',
            'contact' => 'Contact',
            'contact_info' => 'Contact Info',
            'updated_at' => 'Updated At',
            'follow' => 'Follow',
        ];
    }
}
