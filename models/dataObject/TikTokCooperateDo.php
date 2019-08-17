<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_tiktok_cooperate".
 *
 * @property int $id
 * @property string $nickname 昵称
 * @property string $channel 渠道
 * @property int $fans_num 粉丝量
 * @property int $time 时间
 * @property string $authorize_performance 授权平台
 * @property int $authorize_time 授权时间
 * @property string $authorize_remark 授权备注
 * @property string $kol_info KOL具体信息
 * @property string $follow 跟进人
 * @property string $link 链接
 * @property string $draft_quotation 初步报价
 * @property int $draft_verify 初审（0待定/1否/2是）
 * @property string $draft_verify_remark 初审备注
 * @property int $video_num 视频数
 * @property string $final_price 最终价格
 * @property int $final_verify 终审（0待定/1否/2是）
 * @property string $final_verify_remark 终审备注
 * @property string $product 产品
 * @property string $cooperate_pattern 合作模式
 * @property string $dept 部门
 */
class TikTokCooperateDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tiktok_cooperate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname', 'channel', 'fans_num', 'time', 'kol_info', 'follow', 'link', 'draft_quotation', 'draft_verify_remark', 'dept'], 'required'],
            [['fans_num', 'time', 'authorize_time', 'draft_verify', 'video_num', 'final_verify'], 'integer'],
            [['nickname', 'channel', 'authorize_performance', 'authorize_remark', 'kol_info', 'follow', 'link', 'draft_quotation', 'draft_verify_remark', 'final_price', 'final_verify_remark', 'product', 'cooperate_pattern', 'dept'], 'string', 'max' => 255],
            [['nickname'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'channel' => 'Channel',
            'fans_num' => 'Fans Num',
            'time' => 'Time',
            'authorize_performance' => 'Authorize Performance',
            'authorize_time' => 'Authorize Time',
            'authorize_remark' => 'Authorize Remark',
            'kol_info' => 'Kol Info',
            'follow' => 'Follow',
            'link' => 'Link',
            'draft_quotation' => 'Draft Quotation',
            'draft_verify' => 'Draft Verify',
            'draft_verify_remark' => 'Draft Verify Remark',
            'video_num' => 'Video Num',
            'final_price' => 'Final Price',
            'final_verify' => 'Final Verify',
            'final_verify_remark' => 'Final Verify Remark',
            'product' => 'Product',
            'cooperate_pattern' => 'Cooperate Pattern',
            'dept' => 'Dept',
        ];
    }
}
