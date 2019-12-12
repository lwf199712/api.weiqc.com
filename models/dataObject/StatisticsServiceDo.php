<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_statistics_service".
 *
 * @property int $id
 * @property string $name 服务号名称
 * @property string $account 服务账号
 * @property int $created_at 创建时间
 * @property int $updated_time 更新时间
 * @property string $creator 创建人
 * @property int $updated_at 修改时间
 * @property int $deleted_at 删除时间
 * @property string $updater 创建人
 * @property string $deleter 删除人
 */
class StatisticsServiceDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_statistics_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_time', 'updated_at', 'deleted_at'], 'integer'],
            [['name', 'account'], 'string', 'max' => 64],
            [['creator', 'updater', 'deleter'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account' => 'Account',
            'created_at' => 'Created At',
            'updated_time' => 'Updated Time',
            'creator' => 'Creator',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'updater' => 'Updater',
            'deleter' => 'Deleter',
        ];
    }
}
