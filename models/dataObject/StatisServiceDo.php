<?php

namespace app\models\dataObject;

use Yii;

/**
 * This is the model class for table "bm_statis_service".
 *
 * @property int $id
 * @property string $name 服务号名称
 * @property string $account 服务账号
 * @property int $create_time 创建时间
 */
class StatisServiceDo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'bm_statis_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'account', 'create_time'], 'required'],
            [['create_time'], 'integer'],
            [['name', 'account'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account' => 'Account',
            'create_time' => 'Create Time',
        ];
    }
}
