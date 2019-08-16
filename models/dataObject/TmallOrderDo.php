<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_tmall_order".
 *
 * @property int $create_at 创建时间
 * @property string $phone 手机
 * @property int $price 金额(分)
 */
class TmallOrderDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_tmall_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_at', 'phone', 'price'], 'required'],
            [['create_at', 'price'], 'integer'],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'create_at' => 'Create At',
            'phone' => 'Phone',
            'price' => 'Price',
        ];
    }
}
