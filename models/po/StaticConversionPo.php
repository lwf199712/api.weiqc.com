<?php

namespace app\models\po;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%statis_conversion}}".
 *
 * @property int $id
 * @property int $u_id statisUrl表的id
 * @property int $ip
 * @property string $country
 * @property string $area
 * @property int $date
 * @property string $page
 * @property string $referer
 * @property string $agent
 * @property int $createtime
 * @property string $wxh 微信服务号
 */
class StaticConversionPo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName():string
    {
        return '{{%statis_conversion}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['u_id', 'ip', 'date', 'createtime'], 'required'],
            [['u_id', 'ip', 'date', 'createtime'], 'integer'],
            [['country', 'area'], 'string', 'max' => 40],
            [['page', 'referer', 'agent'], 'string', 'max' => 255],
            [['wxh'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels():array
    {
        return [
            'id'         => 'ID',
            'u_id'       => 'statisUrl表的id',
            'ip'         => 'Ip',
            'country'    => '国家',
            'area'       => '区域',
            'date'       => '日期',
            'page'       => '页',
            'referer'    => '引荐',
            'agent'      => '代理人',
            'createtime' => '创建时间',
            'wxh'        => '微信服务号',
        ];
    }
}
