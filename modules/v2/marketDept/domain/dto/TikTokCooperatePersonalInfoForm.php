<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;

class TikTokCooperatePersonalInfoForm extends Model
{

   public $nickname;

   public $channel;

   public $time;

   public $fans_num;

   public $kol_info;

   public $follow;

   public $link;

   public $draft_quotation;

   public $dept;


    public function rules()
    {
        return [
            [['nickname', 'channel', 'fans_num', 'time', 'kol_info', 'follow', 'link', 'draft_quotation','dept'], 'required'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'nickname'              => '昵称',
            'channel'               => '渠道',
            'fans_num'              => '粉丝量',
            'time'                  => '时间',
            'kol_info'              => 'KOL具体信息',
            'follow'                => '跟进人',
            'link'                  => '链接',
            'draft_quotation'       => '初步报价',
            'dept'                  => '部门',
        ];
    }
}