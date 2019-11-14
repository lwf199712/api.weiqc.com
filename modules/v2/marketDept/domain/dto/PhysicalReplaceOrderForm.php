<?php declare(strict_types=1);


namespace app\modules\v2\marketDept\domain\dto;
use yii\base\Model;

/**
 * Class PhysicalReplaceOrderForm
 * @package app\modules\v2\marketDept\domain\dto
 */
class PhysicalReplaceOrderForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $nick_name;
    /** @var string */
    public $we_chat_id;
    /** @var string */
    public $fans_amount;
    /** @var string */
    public $advert_location;
    /** @var string */
    public $put_times;
    /** @var int */
    public $dispatch_time;
    /** @var string */
    public $follower;
    /** @var double */
    public $female_powder_proportion;
    /** @var string */
    public $put_link;
    /** @var string */
    public $replace_product;
    /** @var string */
    public $replace_quantity;
    /** @var string */
    public $brand;
    /** @var string */
    public $average_reading;
    /** @var string */
    public $account_type;
    /** @var string */
    public $advert_read_num;
    /** @var double */
    public $volume_transaction;
    /** @var string */
    public $new_fan_attention;


    public function rules(): array
    {
        return [
            [['nick_name', 'we_chat_id', 'dispatch_time'], 'required'],
            [['id', 'fans_amount', 'put_times', 'replace_quantity', 'average_reading', 'advert_read_num', 'new_fan_attention'], 'integer'],
            [['nick_name', 'we_chat_id', 'dispatch_time', 'advert_location', 'follower', 'put_link', 'replace_product', 'brand', 'account_type'], 'string'],
            [['female_powder_proportion', 'volume_transaction'], 'double'],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'                        => 'ID',
            'nick_name'                 => '昵称',
            'we_chat_id'                => '微信号',
            'fans_amount'               => '粉丝量',
            'advert_location'           => '广告位置',
            'put_times'                 => '投放次数',
            'dispatch_time'             => '发文时间',
            'follower'                  => '跟进人',
            'female_powder_proportion'  => '女粉占比',
            'put_link'                  => '投放链接',
            'replace_product'           => '置换产品',
            'replace_quantity'          => '置换件数',
            'brand'                     => '品牌',
            'average_reading'           => '平均阅读数',
            'account_type'              => '账号类型',
            'advert_read_num'           => '广告阅读数',
            'volume_transaction'        => '成交额',
            'new_fan_attention'         => '新粉关注数',
        ];
    }
}