<?php

namespace app\models\dataObject;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "bm_big_data_statistics".
 *
 * @property int    $id
 * @property string $delivery_number 大投编号
 * @property string $account         账号
 * @property string $channel         渠道
 * @property string $name            负责人
 * @property string $video           视频
 * @property string $page_name       落地页
 * @property string $ecmp            ecmp
 * @property string $price           出价
 * @property string $click_price     点击单价
 * @property string $test_number     测试编号
 * @property string $title           标题
 * @property string $pic             图片路径
 * @property int    $created_date    创建日期
 * @property int    $is_delete       是否删除
 */
class BigDataStatisticsDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%big_data_statistics}}';
    }

    public static function getDb(): Connection
    {
        return Yii::$app->dbToDc;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['delivery_number', 'account', 'channel', 'name', 'video', 'page_name', 'price', 'click_price', 'test_number', 'title', 'pic', 'created_date'], 'required'],
            [['ecmp', 'price', 'click_price'], 'number'],
            [['created_date', 'is_delete'], 'integer'],
            [['delivery_number', 'account', 'page_name', 'test_number', 'title'], 'string', 'max' => 64],
            [['channel', 'name'], 'string', 'max' => 32],
            [['video', 'pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'              => 'ID',
            'delivery_number' => 'Delivery Number',
            'account'         => 'Account',
            'channel'         => 'Channel',
            'name'            => 'Name',
            'video'           => 'Video',
            'page_name'       => 'Page Name',
            'ecmp'            => 'Ecmp',
            'price'           => 'Price',
            'click_price'     => 'Click Price',
            'test_number'     => 'Test Number',
            'title'           => 'Title',
            'pic'             => 'Pic',
            'created_date'    => 'Created Date',
            'is_delete'       => 'Is Delete',
        ];
    }

    public function behaviors(): array
    {
        return [
            'time' => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_date']
                ],
            ]
        ];
    }
}
