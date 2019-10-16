<?php declare(strict_types=1);

namespace app\models\dataObject;

use app\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\UnsetArrayValue;

/**
 * This is the model class for table "bm_statis_url_putvolume".
 *
 * @property int    $id
 * @property string $put_volume      投放量
 * @property string $conversion_cost 转化成本
 * @property int    $date            日期
 * @property int    $statis_url_id   statis_url表id
 * @property string $creator         创建者
 * @property int    $create_time     创建时间
 * @property string $updater         更新者
 * @property int    $update_time     更新时间
 * @property int    $is_delete       删除：0-未删除/1-已删除
 */
class DeliveryVolumeDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%statis_url_putvolume}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['put_volume', 'conversion_cost'], 'number'],
            [['date', 'statis_url_id', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['creator', 'updater'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'              => 'ID',
            'put_volume'      => 'Put Volume',
            'conversion_cost' => 'Conversion Cost',
            'date'            => 'Date',
            'statis_url_id'   => 'Statis Url ID',
            'creator'         => 'Creator',
            'create_time'     => 'Create Time',
            'updater'         => 'Updater',
            'update_time'     => 'Update Time',
            'is_delete'       => 'Is Delete',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creator'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updater'],
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
