<?php

namespace app\models\dataObject;

use app\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_mktad_user".
 *
 * @property int    $id
 * @property string $username    录入投放数据人的姓名
 * @property int    $is_delete   是否删除，0/未删除，1/已删除
 * @property string $creater     创建人
 * @property int    $create_time 创建时间
 * @property string $update_user 修改人
 * @property int    $update_time 修改时间
 */
class MktadUserDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%mktad_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['is_delete', 'create_time', 'update_time'], 'integer'],
            [['username', 'creater', 'update_user'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'username'    => 'Username',
            'is_delete'   => 'Is Delete',
            'creater'     => 'Creater',
            'create_time' => 'Create Time',
            'update_user' => 'Update User',
            'update_time' => 'Update Time',
        ];
    }

    public function behaviors(): array
    {
        return [
            'time'   => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
            'author' => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creater'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_user'],
                ],
                'value'      => static function () {
                    /** @var User $user */
                    $user = Yii::$app->user->identity;
                    return $user->username;
                },
            ],
        ];
    }
}
