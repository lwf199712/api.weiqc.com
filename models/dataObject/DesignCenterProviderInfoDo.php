<?php
declare(strict_types=1);
namespace app\models\dataObject;

use app\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "bm_design_center_provider_info".
 * @package app\models\dataObject
 *
 * @property int $id
 * @property string $name               视频供应商/外包设计公司
 * @property string $quoted_price       报价
 * @property string $site               地点
 * @property string $recommended_reason 推荐理由
 * @property string $contact_way        联系方式
 * @property string $remark             备注
 * @property string $reference_case     参考案例
 * @property string $flag               标识（video/outer）
 * @property int    $created_at         创建时间
 * @property int    $updated_at         更新时间
 * @property int    $creator            创建人
 * @property int    $updater            更新人
 */
class DesignCenterProviderInfoDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return '{{%design_center_provider_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['created_at', 'updated_at'], 'string'],
            [['name', 'quoted_price', 'site', 'recommended_reason', 'contact_way', 'remark', 'reference_case', 'flag', 'creator', 'updater'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                 => 'ID',
            'name'               => 'Name',
            'quoted_price'       => 'Quoted Price',
            'site'               => 'Site',
            'recommended_reason' => 'Recommended Reason',
            'contact_way'        => 'Contact Way',
            'remark'             => 'Remark',
            'reference_case'     => 'Reference Case',
            'flag'               => 'Flag',
            'created_at'         => 'created_at',
            'updated_at'         => 'updated_at',
            'creator'            => 'creator',
            'updater'            => 'updater',
        ];
    }

    /**
     * 行为事件
     * @return array
     * @author weifeng
     */

    public function behaviors(): array
    {
        return [
            'author' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => 'creator',
                    self::EVENT_BEFORE_UPDATE => 'updater',
                ],
                'value' => static function () {
                    $id = Yii::$app->user->getId();
                    /** @var User $user */
                    $user = User::findOne(['id' => $id]);
                    return $user->realname;
                }
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => 'created_at',
                    self::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => static function () {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }
}