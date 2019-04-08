<?php

namespace app\modules\v1\conversion\domain;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%statis_service_conversions}}".
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
 */
class StaticServiceConversions extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%statis_service_conversions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['u_id', 'create_time'], 'required'],
            [['u_id', 'conversions', 'conversions_time', 'create_time'], 'integer'],
            [['pattern'], 'string', 'max' => 13],
            [['service'], 'string', 'max' => 64],
            [['service_list', 'conversions_list'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'               => 'ID',
            'u_id'             => '父级连接id(对应statis_url表)',
            'pattern'          => '模式(0/不循环 1/按小时循环 2/按转化数循环)',
            'service'          => '当前服务号',
            'service_list'     => '服务号列表',
            'conversions_list' => '目标转化数列表',
            'conversions'      => '当前服务号转化数',
            'conversions_time' => '开始转化时间',
            'create_time'      => '创建时间',
        ];
    }
}
