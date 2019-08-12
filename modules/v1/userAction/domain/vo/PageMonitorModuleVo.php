<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\domain\vo;


use yii\base\Model;

/**
 * Class PageMonitorModuleVo
 * @property string $ip
 * @property int    $url_id
 * @property int    $page_id
 * @property int    $current_module
 * @property int    $duration
 * @property int    $create_time
 * @package app\modules\v1\userAction\domain\vo
 * @author zhuozhen
 */
class PageMonitorModuleVo extends Model
{
    /** @var string */
    public $ip;
    /** @var int */
    public $url_id;
    /** @var int */
    public $page_id;
    /** @var int */
    public $current_module;
    /** @var int */
    public $duration;
    /** @var int */
    public $create_time;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: zhuozhen
     */
    public function rules(): array
    {
        return [
            [['ip'], 'string', 'max' => 128],
            [['url_id' . 'page_id', 'current_module', 'duration', 'create_time'], 'integer', 'max' => 11],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: zhuozhen
     */
    public function attributeLabels(): array
    {
        return [
            'url_id'         => 'status_url表主键id',
            'ip'             => 'IP',
            'page_id'        => 'page_monitor_page表主键id',
            'current_module' => '当前模块数',
            'duration'       => '当前模块访问时长（秒）',
            'create_time'    => '创建时间',
        ];
    }
}