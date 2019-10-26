<?php

declare(strict_types=1);

namespace app\models\dataObject;

use app\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "bm_mktad".
 *
 * @property int    $id
 * @property int    $write_time  录入时间
 * @property int    $u_id        投放人员id
 * @property int    $s_id        关联服务号表的id
 * @property int    $is_delete   是否删除，0/未删除，1/已删除
 * @property string $creater     创建人
 * @property int    $create_time 创建时间
 * @property string $update_user 修改人
 * @property int    $update_time 修改时间
 */
class MktadDo extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%mktad}}';
    }

    public static function getDb(): Connection
    {
        return Yii::$app->dbToDc;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['write_time', 'u_id', 's_id', 'is_delete', 'create_time', 'update_time'], 'integer'],
            [['creater', 'update_user'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'write_time'  => 'Write Time',
            'u_id'        => 'U ID',
            's_id'        => 'S ID',
            'is_delete'   => 'Is Delete',
            'creater'     => 'Creater',
            'create_time' => 'Create Time',
            'update_user' => 'Update User',
            'update_time' => 'Update Time'
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

    /**
     * 关联mktad_detail表
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/9/27
     */
    public function getMktadDetail(): ActiveQuery
    {
        return $this->hasMany(MktadDetailDo::class, ['mkt_id' => 'id'])->alias('md');
    }

    /**
     * 多表关联
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/10/6
     */
    public function getMktadVideo(): ActiveQuery
    {
        return $this->hasMany(MktadVideoDo::class, ['id' => 'v_id'])->via('mktadDetail');
    }

    /**
     * 关联mktad_user表
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/9/27
     */
    public function getMktadUser(): ActiveQuery
    {
        return $this->hasOne(MktadUserDo::class, ['id' => 'u_id']);
    }

    /**
     * 关联mktad_service表
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/9/27
     */
    public function getMktadService(): ActiveQuery
    {
        return $this->hasOne(MktadServiceDo::class, ['id' => 's_id']);
    }
}
