<?php

namespace app\models\dataObject;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Static url table
 * This is the model class for table "{{%static_url}}".
 *
 * @property int    $id         id
 * @property int    $m_id       member表对应的id
 * @property string $ident      token值
 * @property string $name       名字
 * @property string $url        链接地址
 * @property int    $hits       点击
 * @property int    $client     客户
 * @property int    $visit      访问量
 * @property int    $lasttime   上一次的事件
 * @property int    $createtime 创建时间
 * @property string $recycle    回首
 * @property string $pcurl      pc链接
 * @property int    $group_id   组别id
 */
class StaticUrlDo extends ActiveRecord
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
            [['m_id', 'ident', 'name', 'url'], 'required'],
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
            'lasttime'   => '上一次的时间',
            'createtime' => '创建时间',
            'recycle'    => '回首',
            'pcurl'      => 'pc链接',
            'group_id'   => '组别id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'createtime' => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'createtime',
                ],
                'value'      => time(),
            ],
            'lasttime'   => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'lasttime',
                ],
                'value'      => time(),
            ],
        ];
    }


    public function getStaticUrlGroup() : ActiveQuery
    {
        return $this->hasOne(StaticUrlGroup::class, ['id' => 'group_id'])->alias('staticUrlGroup');
    }

    public function getStaticServiceConversions() : ActiveQuery
    {
        return $this->hasOne(StaticServiceConversions::class, ['u_id' => 'u_id'])->alias('staticServiceConversions');
    }

    public function getMember() : ActiveQuery
    {
        return $this->hasOne(Member::class, ['id' => 'm_id'])->alias('member')->select(['id','username']);
    }
}
