<?php
declare(strict_types=1);


namespace app\modules\v2\marketDept\domain\dto;
use yii\base\Model;

/**
 * Class PhysicalSendStatusForm
 * @package app\modules\v2\marketDept\domain\dto
 */
class PhysicalSendStatusForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $recipients;
    /** @var string */
    public $phone;
    /** @var string */
    public $delivery_site;
    /** @var string */
    public $tracking_number;

    public function rules(): array
    {
        return [
            [['id', 'tracking_number', 'phone'], 'integer'],
            [['recipients', 'delivery_site'], 'string'],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'               => 'ID',
            'recipients'       => '收件人',
            'phone'            => '联系电话',
            'delivery_site'    => '收货地址',
            'tracking_number'  => '快递单号'
        ];
    }
}