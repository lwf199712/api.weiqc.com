<?php declare(strict_types=1);

namespace app\models\dataObject;

use yii\db\ActiveRecord;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use app\models\User;

/**
 * Class StatisticsUrlGroupChannelDo
 * @package app\models\dataObject
 * @property int $id
 * @property string $channel_name
 * @property int $is_delete
 * @property string $creator
 * @property int $created_at
 * @property string $updater
 * @property int $updated_at
 */
class StatisticsUrlGroupChannelDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%statistics_url_group_channel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_delete'], 'integer'],
            [['channel_name', 'updater', 'creator'], 'string', 'max' => 64]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'channel_name' => '渠道名称',
            'is_delete' => '是否删除',
            'creator' => '创建人',
            'created_at' => '创建时间',
            'updater' => '更新者',
            'updated_at' => '更新时间',
        ];
    }


    public function behaviors(): array
    {
        return [
            'time' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            'author' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creator'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updater'],
                ],
                'value' => static function () {
                    /** @var User $user */
                    $user = Yii::$app->user->identity;
                    return $user->username;
                },
            ],
        ];
    }

}
