<?php

namespace app\models\dataObject;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_statistics_service".
 *
 * @property int $id
 * @property string $name 服务号名称
 * @property string $account 服务账号
 * @property int $create_time 创建时间
 * @property int $is_delete 软删除（0：未删除，1：删除）
 * @property int $update_time 更新时间
 * @property string $creator 创建人
 */
class StatisticsServiceDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'bm_statistics_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['create_time', 'is_delete', 'update_time'], 'integer'],
            [['name', 'account'], 'string', 'max' => 64],
            [['creator'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels():array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account' => 'Account',
            'create_time' => 'Create Time',
            'is_delete' => 'Is Delete',
            'update_time' => 'Update Time',
            'creator' => 'Creator',
        ];
    }

    public function behaviors():array
    {
        return [
            'time'   => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
            'creator' => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creator'],
                ],
                'value'      => static function () {
                    return Yii::$app->user->identity->username;

                },
            ],
        ];
    }
}
