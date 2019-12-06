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
 * @property int $created_at 创建时间
 * @property int $updated_time 更新时间
 * @property string $creator 创建人
 * @property int $updated_at 修改时间
 * @property int $deleted_at 删除时间
 * @property string $updater 创建人
 * @property string $deleter 删除人
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
            [['created_at', 'updated_time', 'updated_at', 'deleted_at'], 'integer'],
            [['name', 'account'], 'string', 'max' => 64],
            [['creator', 'updater', 'deleter'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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

    public function behaviors(): array
    {
        return [
            'time'   => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
            ],
            'creator' => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creator'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updater'],
                ],
                'value' => static function(){
                    return Yii::$app->user->identity->username;
                }
            ],



        ];
    }

}
