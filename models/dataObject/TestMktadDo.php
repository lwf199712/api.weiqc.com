<?php

namespace app\models\dataObject;

use app\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "bm_test_mktad".
 *
 * @property int    $id
 * @property int    $u_id         跟进人，mktad_user表id
 * @property int    $r_id         渠道，mktad_rebate表id，用来计算实际成本
 * @property string $page_title   落地页名
 * @property string $link         链接地址
 * @property int    $consume      消耗
 * @property int    $fans_num     加粉数
 * @property int    $turnover     成交金额
 * @property int    $is_delete    是否删除，0/未删除，1/已删除
 * @property string $creater      创建人
 * @property int    $create_time  日期
 * @property string $update_user
 * @property int    $update_time
 * @property int    $v_id         视频，mktad_video表id
 * @property string $remark       备注
 * @property string $test_number  测试编号
 * @property string $title        标题
 * @property string $pic          图片路径
 * @property int    $created_date 记录创建时间
 * @property int    $a_id         帐号id，对应帐号表的id
 */
class TestMktadDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%test_mktad}}';
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
            [['u_id', 'r_id', 'consume', 'fans_num', 'turnover', 'is_delete', 'create_time', 'update_time', 'v_id', 'created_date', 'a_id'], 'integer'],
            [['test_number', 'title', 'pic'], 'required'],
            [['page_title', 'creater', 'update_user'], 'string', 'max' => 32],
            [['link', 'remark', 'pic'], 'string', 'max' => 255],
            [['test_number', 'title'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => 'ID',
            'u_id'         => 'U ID',
            'r_id'         => 'R ID',
            'page_title'   => 'Page Title',
            'link'         => 'Link',
            'consume'      => 'Consume',
            'fans_num'     => 'Fans Num',
            'turnover'     => 'Turnover',
            'is_delete'    => 'Is Delete',
            'creater'      => 'Creater',
            'create_time'  => 'Create Time',
            'update_user'  => 'Update User',
            'update_time'  => 'Update Time',
            'v_id'         => 'V ID',
            'remark'       => 'Remark',
            'test_number'  => 'Test Number',
            'title'        => 'Title',
            'pic'          => 'Pic',
            'created_date' => 'Created Date',
            'a_id'         => 'A ID',
        ];
    }

    public function behaviors(): array
    {
        return [
            'time'   => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_date'],
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
     * 关联testmktad_detail表
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/9/28
     */
    public function getTestMktadDetail(): ActiveQuery
    {
        return $this->hasMany(TestMktadDetailDo::class, ['t_id' => 'id']);
    }

    /**
     * 关联big_data_statistics表
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/9/28
     */
    public function getBigDataStatistics(): ActiveQuery
    {
        return $this->hasOne(BigDataStatisticsDo::class, ['test_number' => 'test_number'])->select('id,test_number');
    }
}
