<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_design_center_home_video".
 *
 * @property int $id
 * @property int $design_finish_time 设计完成时间
 * @property string $name 名称
 * @property string $video 图片地址
 * @property int $upload_time 上传时间
 * @property int $audit_status 审核状态(0/待审核，1/通过，2/不通过)
 * @property string $audit_opinion 审核意见
 * @property string $auditor 审核人
 * @property int $audit_time 审核时间
 * @property string $category 属性类别
 * @property string $url 图片链接
 */
class DesignCenterVideoDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_design_center_home_video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['design_finish_time', 'upload_time', 'audit_status', 'audit_time'], 'integer'],
            [['name'], 'required'],
            [['name', 'audit_opinion', 'auditor'], 'string', 'max' => 255],
            [['video', 'url'], 'string', 'max' => 1024],
            [['category'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'design_finish_time' => 'Design Finish Time',
            'name' => 'Name',
            'video' => 'Video',
            'upload_time' => 'Upload Time',
            'audit_status' => 'Audit Status',
            'audit_opinion' => 'Audit Opinion',
            'auditor' => 'Auditor',
            'audit_time' => 'Audit Time',
            'category' => 'Category',
            'url' => 'Url',
        ];
    }
}
