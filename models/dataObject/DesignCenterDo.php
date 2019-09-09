<?php

namespace app\models\dataObject;

use app\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_design_center".
 * @package app\models\dataObject
 *
 * @property int $id
 * @property string $version 版本
 * @property string $name 名称
 * @property string $stylist 设计师
 * @property string $picture_address 图片地址
 * @property int $upload_time 上传时间
 * @property int $audit_status 审核状态(0/待审核，1/通过，2/不通过)
 * @property string $audit_opinion 审核意见
 * @property string $auditor 审核人
 * @property int $audit_time 审核时间
 */
class DesignCenterDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%design_center}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['version', 'name', 'stylist', 'picture_address'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'version' => 'Version',
            'name' => 'Name',
            'stylist' => 'Stylist',
            'picture_address' => 'Picture Address',
            'upload_time' => 'Upload Time',
            'audit_status' => 'Audit Status',
            'audit_opinion' => 'Audit Opinion',
            'auditor' => 'Auditor',
            'audit_time' => 'Audit Time',
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_UPDATE => 'auditor',
                ],
                'value' => static function () {
                    $id = \Yii::$app->user->getId();
                    /** @var User $user */
                    $user = User::findOne(['id' => $id]);
                    return $user->username;
                }
            ]
        ];
    }
}