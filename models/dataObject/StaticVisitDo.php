<?php


namespace app\models\dataObject;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%statis_visit}}".
 *
 * @property string $id
 * @property int $u_id
 * @property int $ip
 * @property string $country
 * @property string $area
 * @property int $date
 * @property string $page
 * @property string $referer
 * @property string $agent
 * @property int $createtime
 */
class StaticVisitDo extends  ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%statis_visit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['u_id', 'ip', 'date', 'createtime'], 'required'],
            [['u_id', 'ip', 'date', 'createtime'], 'integer'],
            [['country', 'area'], 'string', 'max' => 40],
            [['page', 'referer'], 'string', 'max' => 255],
            ['agent','string','max' => 325],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'u_id'       => 'statis_url表id',
            'ip'         => 'IP地址',
            'country'    => '国家',
            'area'       => '区域',
            'date'       => '日期',
            'page'       => '页',
            'referer'    => '引荐',
            'agent'      => '代理人',
            'createtime' => '创建时间',
        ];
    }
}