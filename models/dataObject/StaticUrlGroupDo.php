<?php

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_statis_url_group".
 *
 * @property int $id
 * @property string $groupname 分组名称
 * @property string $desc 描述
 * @property int $parent
 * @property string $sort
 * @property string $user_name 投放数据-录入人员姓名
 * @property int $mktad_user_id 投放数据-录入人员表id
 * @property string $channel_name 分组渠道名
 * @property int $group_channel_id 分组渠道表id
 */
class StaticUrlGroupDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%statis_url_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'mktad_user_id', 'group_channel_id'], 'integer'],
            [['groupname'], 'string', 'max' => 64],
            [['desc'], 'string', 'max' => 256],
            [['sort'], 'string', 'max' => 32],
            [['user_name', 'channel_name'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupname' => 'Groupname',
            'desc' => 'Desc',
            'parent' => 'Parent',
            'sort' => 'Sort',
            'user_name' => 'User Name',
            'mktad_user_id' => 'Mktad User ID',
            'channel_name' => 'Channel Name',
            'group_channel_id' => 'Group Channel ID',
        ];
    }
}
