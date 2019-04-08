<?php

namespace app\modules\conversion\domain;

use yii\db\ActiveRecord;

/**
 * Static url table
 * This is the model class for table "{{%static_url}}".
 *
 * @property int $id id
 * @property int $m_id member表对应的id
 * @property string $ident token值
 * @property string $name 名字
 * @property string $url 链接地址
 * @property int $hits 点击
 * @property int $client 客户
 * @property int $visit 访问量
 * @property int $lasttime 上一次的事件
 * @property int $createtime 创建时间
 * @property string $recycle 回首
 * @property string $pcurl pc链接
 * @property int $group_id 组别id
 */
class StaticUrl extends ActiveRecord
{
    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     * @author: lirong
     */
    public static function tableName(): string
    {
        return '{{%statis_url}}';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['m_id', 'ident', 'name', 'url', 'lasttime', 'createtime'], 'required'],
            [['m_id', 'hits', 'client', 'visit', 'lasttime', 'createtime', 'group_id'], 'integer'],
            [['recycle'], 'string'],
            [['ident'], 'string', 'max' => 13],
            [['name'], 'string', 'max' => 80],
            [['url', 'pcurl'], 'string', 'max' => 255],
            [['ident'], 'unique'],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'm_id'       => 'member表对应的id',
            'ident'      => 'token值',
            'name'       => '名字',
            'url'        => '链接地址',
            'hits'       => '点击',
            'client'     => '客户',
            'visit'      => '访问量',
            'lasttime'   => '上一次的事件',
            'createtime' => '创建时间',
            'recycle'    => '回首',
            'pcurl'      => 'pc链接',
            'group_id'   => '组别id',
        ];
    }
}
