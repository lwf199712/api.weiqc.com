<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_statis_service_conversions".
 *
 * @property int $id
 * @property int $u_id 父级连接id(对应statis_url表)
 * @property string $pattern 模式(0/不循环 1/按小时循环 2/按转化数循环)
 * @property string $service 当前服务号
 * @property string $service_list 服务号列表
 * @property string $conversions_list 目标转化数列表
 * @property int $conversions 当前服务号转化数
 * @property int $conversions_time 开始转化时间
 * @property int $create_time 创建时间
 * @property string $original_service 最初设置的公众号
 */
class StaticServiceConversions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_statis_service_conversions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['u_id', 'create_time'], 'required'],
            [['u_id', 'conversions', 'conversions_time', 'create_time'], 'integer'],
            [['pattern'], 'string', 'max' => 13],
            [['service'], 'string', 'max' => 64],
            [['service_list', 'conversions_list'], 'string', 'max' => 255],
            [['original_service'], 'string', 'max' => 18],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'u_id' => 'U ID',
            'pattern' => 'Pattern',
            'service' => 'Service',
            'service_list' => 'Service List',
            'conversions_list' => 'Conversions List',
            'conversions' => 'Conversions',
            'conversions_time' => 'Conversions Time',
            'create_time' => 'Create Time',
            'original_service' => 'Original Service',
        ];
    }
}
