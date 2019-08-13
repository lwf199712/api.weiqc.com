<?php

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_page_monitor_module".
 *
 * @property int $id
 * @property int $url_id status_url表主键id
 * @property int $ip ip地址
 * @property int $page_id page_monitor_page表主键id
 * @property int $current_module 当前模块数
 * @property int $duration 当前模块访问时长（秒）
 * @property int $create_time 创建时间
 */
class PageMonitorModuleDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_monitor_module}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_id', 'ip', 'page_id', 'current_module', 'duration', 'create_time'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_id' => 'Url ID',
            'ip' => 'Ip',
            'page_id' => 'Page ID',
            'current_module' => 'Current Module',
            'duration' => 'Duration',
            'create_time' => 'Create Time',
        ];
    }
}
