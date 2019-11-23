<?php

namespace app\models\dataObject;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_CategoryManagement".
 *
 * @property int $id
 * @property string $category 类别内容
 * @property int $type 1 是图片的类别属性，2是视频的类别属性
 */
class CategoryManagementDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_CategoryManagement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['type'], 'integer'],
            [['category'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'type' => 'Type',
        ];
    }
}
