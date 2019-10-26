<?php

namespace app\models\dataObject;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_design_center_image".
 * @package app\models\dataObject
 *
 * @property int $id
 * @property string $design_finish_time 设计完成时间
 * @property string $name 名称
 * @property string $stylist 设计师
 * @property string $picture_address 图片地址
 * @property int $upload_time 上传时间
 * @property int $audit_status 审核状态(0/待审核，1/通过，2/不通过)
 * @property string $audit_opinion 审核意见
 * @property string $auditor 审核人
 * @property int $audit_time 审核时间
 * @property string $size 图片规格
 * @property string $type 类型（homePage/首页图片，mainImage/主图，productDetail/产品详情，drillShow/钻展，throughCar/直通车，landingPage/落地页）
 */
class DesignCenterImageDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return '{{%design_center_image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['design_finish_time', 'upload_time', 'audit_status', 'audit_time'], 'integer'],
            [['name', 'stylist', 'audit_opinion', 'auditor', 'size', 'type'], 'string'],
            [['picture_address'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'design_finish_time' => 'Design Finish Time',
            'name' => 'Name',
            'stylist' => 'Stylist',
            'picture_address' => 'Picture Address',
            'upload_time' => 'Upload Time',
            'audit_status' => 'Audit Status',
            'audit_opinion' => 'Audit Opinion',
            'auditor' => 'Auditor',
            'audit_time' => 'Audit Time',
            'size' => 'Size',
            'type' => 'Type',
        ];
    }

}