<?php

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "bm_tiktok_resource_base".
 *
 * @property int               $id
 * @property string            $mcn_company_name  机构/公司名称
 * @property string            $header_account    头部账号
 * @property string            $cooperate_info    合作情况
 * @property string            $company_business  公司主要业务
 * @property string            $company_address   公司地址
 * @property int               $identity          MCN机构、中介、个人
 * @property string            $account_num       账号总数量
 * @property string            $single_account    独家账号
 * @property string            $depend_account    挂靠账号
 * @property string            $cooperate_channel 可合作渠道
 * @property string            $main_account_type 主要账号类型
 * @property int               $fans_num          总粉丝数量(W)
 * @property int               $cooperate_num     已合作达人数
 * @property int               $cooperate_fee     已合作总费用(W)
 * @property int               $per_month_fee     每月接单费用(W)
 * @property string            $publication       刊例
 * @property int               $update_at         更新时间
 * @property string            $follow            跟进人
 * @property-read  ActiveQuery $tikTokResourceBaseCooperateDo
 */
class TikTokResourceBaseDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_tiktok_resource_base';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mcn_company_name', 'follow'], 'required'],
            [['header_account', 'cooperate_info', 'company_business', 'company_address'], 'string'],
            [['identity', 'fans_num', 'cooperate_num', 'cooperate_fee', 'per_month_fee', 'update_at'], 'integer'],
            [['mcn_company_name', 'account_num', 'single_account', 'depend_account', 'cooperate_channel', 'main_account_type', 'publication', 'follow'], 'string', 'max' => 255],
            [['mcn_company_name', 'follow'], 'unique', 'targetAttribute' => ['mcn_company_name', 'follow']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'mcn_company_name'  => '机构/公司名称',
            'header_account'    => '头部账号',
            'cooperate_info'    => '合作情况',
            'company_business'  => '公司主要业务',
            'company_address'   => '公司地址',
            'identity'          => '身份',
            'account_num'       => '账号总数量',
            'single_account'    => '独家账号',
            'depend_account'    => '挂靠账号',
            'cooperate_channel' => '可合作渠道',
            'main_account_type' => '主要账号类型',
            'fans_num'          => '总粉丝数量（W）',
            'cooperate_num'     => '已合作达人数',
            'cooperate_fee'     => '已合作总费用(W)',
            'per_month_fee'     => '每月接单费用(W)',
            'publication'       => '刊例',
            'update_at'         => '更新时间',
            'follow'            => '跟进人',
        ];
    }

    public function getTikTokResourceBaseCooperateDo(): ActiveQuery
    {
        return $this->hasMany(TikTokResourceBaseCooperateDo::class, ['resource_base_id' => 'id']);
    }
}
