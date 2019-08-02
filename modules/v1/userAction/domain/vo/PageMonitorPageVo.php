<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\domain\vo;

use yii\base\Model;

/**
 * Class PageMonitorPageVo
 * @property string $ip
 * @property int    $current_page
 * @property int    $duration
 * @property int    $total_duration
 * @property int    $url_id
 * @property int    $total_page
 * @property int    $total_module
 * @property int    $visit_deep
 * @property int    $jumpout_module
 * @property int    $jumpout_url
 * @property int    $create_time
 * @package app\modules\v1\userAction\domain\vo
 * @author  zhuozhen
 */
class PageMonitorPageVo extends Model
{
    /** @var string */
    protected $ip;
    /** @var int */
    protected $current_page;
    /** @var int */
    protected $duration;
    /** @var int */
    protected $total_duration;
    /** @var int */
    protected $url_id;
    /** @var int */
    protected $total_page;
    /** @var int */
    protected $total_module;
    /** @var int */
    protected $visit_deep;
    /** @var int */
    protected $jumpout_module;
    /** @var int */
    protected $jumpout_url;
    /** @var int */
    protected $create_time;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['ip'], 'string', 'max' => 128],
            [['current_page' . 'duration', 'total_duration', 'url_id', 'total_page', 'total_module', 'visit_deep', 'jumpout_module', 'jumpout_url', 'create_time'], 'integer', 'max' => 11],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'url_id'         => 'status_url表主键id',
            'ip'             => 'IP',
            'current_page'   => '当前页码',
            'duration'       => '当前页面访问时长（秒）',
            'total_duration' => '此ip访问所有页面总时长（秒）',
            'total_page'     => '总页数',
            'total_module'   => '总模块数',
            'visit_deep'     => '访问模块深度',
            'jumpout_module' => '离开模块深度',
            'jumpout_url'    => '跳出页面的URL',
            'create_time'    => '创建时间',
        ];
    }
}