<?php

namespace app\models\dataObject;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_physical_send_status".
 * @package app\models\dataObject
 * @property int       $id
 * @property string    $rp_id           置换订单id
 * @property string    $recipients      收件人
 * @property string    $phone           联系电话
 * @property string    $delivery_site   收货地址
 * @property string    $tracking_number 快递单号
 */
class PhysicalSendStatusDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%physical_send_status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['tracking_number', 'required'],
            [['recipients', 'phone', 'delivery_site', 'tracking_number'], 'string', 'max' => 255],
            [['id', 'rp_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                => 'ID',
            'recipients'        => 'Recipients',
            'phone'             => 'Phone',
            'delivery_site'     => 'Delivery Site',
            'tracking_number'   => 'Tracking Number',
            'rp_id'             => 'Rp Id',
        ];
    }

}