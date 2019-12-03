<?php declare(strict_types=1);

namespace app\models\dataObject;

use yii\db\ActiveRecord;


/**
 * Class StatisticsUrlGroupChannelDo
 * @package app\models\dataObject
 * @property int $id
 * @property string $channel_name
 * @property int $is_delete
 * @property string $creator
 * @property int $create_time
 * @property string $updater
 * @property int $update_time
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
            [['id', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['channel_name', 'updater', 'creator'], 'string', 'max' => 64],
            [['channel_name', 'creator', 'create_time', 'updater', 'update_time', 'is_delete'], 'required']

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'channel_name' => '渠道名称',
            'is_delete' => '是否删除',
            'creator' => '创建人',
            'create_time' => '创建时间',
            'updater' => '更新者',
            'update_time' => '更新时间',
        ];
    }

}
