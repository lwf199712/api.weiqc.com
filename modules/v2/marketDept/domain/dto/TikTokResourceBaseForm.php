<?php


namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;

class TikTokResourceBaseForm extends Model
{
    /** @var string */
    public $id;
    /** @var string */
    public $mcn_company_name;
    /** @var string */
    public $header_account;
    /** @var string */
    public $cooperate_info;
    /** @var string */
    public $company_business;
    /** @var string */
    public $company_address;
    /** @var string */
    public $identity;
    /** @var string */
    public $account_num;
    /** @var string */
    public $single_account;
    /** @var string */
    public $depend_account;
    /** @var string */
    public $cooperate_channel;
    /** @var string */
    public $main_account_type;
    /** @var string */
    public $fans_num;
    /** @var string */
    public $cooperate_num;
    /** @var string */
    public $cooperate_fee;
    /** @var string */
    public $per_month_fee;
    /** @var string */
    public $publication;
    /** @var string */
    public $update_at;
    /** @var string */
    public $follow;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','mcn_company_name', 'follow'], 'required'],
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
}