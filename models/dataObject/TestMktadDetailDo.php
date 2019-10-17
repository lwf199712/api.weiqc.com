<?php

namespace app\models\dataObject;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "bm_testmktad_detail".
 *
 * @property int    $id
 * @property int    $t_id                   测试数据统计id
 * @property int    $create_time            测试时间
 * @property string $name                   录入人
 * @property string $price                  出价
 * @property string $click_rate             点击率
 * @property string $conversion_rate        转化率
 * @property int    $fans_num               加粉
 * @property string $leakage_rate           漏粉率
 * @property string $ecpm                   ecpm
 * @property string $click_price            点击单价
 * @property int    $consume                消耗
 * @property int    $created_date           创建时间
 * @property int    $r_id                   渠道返点
 * @property int    $turnover               成交金额
 * @property string $remark                 备注
 * @property int    $under_seventeen_count  17岁以下数量
 * @property int    $silence_count          沉默数
 * @property int    $cancel_attention_count 取关数
 */
class TestMktadDetailDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%testmktad_detail}}';
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
            [['t_id', 'create_time', 'fans_num', 'consume', 'created_date', 'r_id', 'turnover', 'under_seventeen_count', 'silence_count', 'cancel_attention_count'], 'integer'],
            [['create_time', 'name', 'price', 'click_price', 'created_date', 'remark'], 'required'],
            [['price', 'click_price'], 'number'],
            [['name'], 'string', 'max' => 32],
            [['click_rate', 'conversion_rate', 'leakage_rate', 'ecpm'], 'string', 'max' => 10],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                     => 'ID',
            't_id'                   => 'T ID',
            'create_time'            => 'Create Time',
            'name'                   => 'Name',
            'price'                  => 'Price',
            'click_rate'             => 'Click Rate',
            'conversion_rate'        => 'Conversion Rate',
            'fans_num'               => 'Fans Num',
            'leakage_rate'           => 'Leakage Rate',
            'ecpm'                   => 'Ecpm',
            'click_price'            => 'Click Price',
            'consume'                => 'Consume',
            'created_date'           => 'Created Date',
            'r_id'                   => 'R ID',
            'turnover'               => 'Turnover',
            'remark'                 => 'Remark',
            'under_seventeen_count'  => 'Under Seventeen Count',
            'silence_count'          => 'Silence Count',
            'cancel_attention_count' => 'Cancel Attention Count',
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
