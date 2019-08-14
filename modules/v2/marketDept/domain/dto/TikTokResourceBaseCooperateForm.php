<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;

class TikTokResourceBaseCooperateForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $channel;
    /** @var string */
    public $kol_name;
    /** @var string */
    public $account_id;
    /** @var string */
    public $talent_introduction;
    /** @var string */
    public $account_type;
    /** @var string */
    public $account_link;
    /** @var double */
    public $fans_num;
    /** @var double */
    public $quotation;
    /** @var int */
    public $cooperate_video_num;
    /** @var double */
    public $cooperate_fee;
    /** @var string */
    public $cooperate_info;
    /** @var string */
    public $contact;
    /** @var string */
    public $contact_info;
    /** @var string */
    public $updated_at;
    /** @var string */
    public $follow;


    public function rules()
    {
        return [
            [['channel','kol_name','account_id','talent_introduction','account_type','account_link','cooperate_info','contact','contact_info','updated_at','follow'],'string'],
            [['cooperate_video_num'],'integer'],
            [['fans_num','quotation','cooperate_fee'],'double']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel' => '渠道',
            'kol_name' => 'KOL昵称',
            'account_id' => '账号ID',
            'talent_introduction' => '达人简介',
            'account_type' => '账号类型(标签)',
            'account_link' => '账号链接',
            'fans_num' => '粉丝量（W）',
            'quotation' => '合作价格/报价（W）',
            'cooperate_video_num' => '已合作视频数',
            'cooperate_fee' => '已合作费用（W）',
            'cooperate_info' => '合作情况',
            'contact' => '联系人',
            'contact_info' => '联系方式',
            'updated_at' => '更新时间',
            'follow' => '跟进人',
        ];
    }
}