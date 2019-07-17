<?php

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_page_monitor_page".
 *
 * @property int $id
 * @property int $url_id status_url表主键id
 * @property int $ip ip地址
 * @property int $current_page 当前页码
 * @property int $duration 当前页面访问时长（秒）
 * @property int $total_duration 此ip访问所有页面总时长（秒）
 * @property int $total_page 总页数
 * @property int $total_module 总模块数
 * @property int $visit_deep 访问模块深度
 * @property int $jumpout_module 离开模块深度
 * @property string $jumpout_url 跳出页面的URL
 * @property int $create_time 创建时间
 */
class PageMonitorPageDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_monitor_page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_id', 'ip', 'current_page', 'duration', 'total_duration', 'total_page', 'total_module', 'visit_deep', 'jumpout_module', 'create_time'], 'integer'],
            [['jumpout_url'], 'string', 'max' => 1024],
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
            'current_page' => 'Current Page',
            'duration' => 'Duration',
            'total_duration' => 'Total Duration',
            'total_page' => 'Total Page',
            'total_module' => 'Total Module',
            'visit_deep' => 'Visit Deep',
            'jumpout_module' => 'Jumpout Module',
            'jumpout_url' => 'Jumpout Url',
            'create_time' => 'Create Time',
        ];
    }

}
