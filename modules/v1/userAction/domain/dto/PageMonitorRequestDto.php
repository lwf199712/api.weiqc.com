<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\domain\dto;

use yii\base\Model;

/**
 * Class PageMonitorRequestDto
 * @property string $token
 * @property int    $current_page
 * @property int    $duration
 * @property int    $total_page
 * @property int    $total_module
 * @property int    $visit_deep
 * @property int    $jumpout_module
 * @property int    $current_module
 * @property int    $module_duration
 * @package app\modules\v1\userAction\domain\dto
 * @author zhuozhen
 */
class PageMonitorRequestDto extends Model
{
    /** @var string */
    public $token;
    /** @var int */
    public $current_page;
    /** @var int */
    public $duration;
    /** @var int */
    public $total_page;
    /** @var int */
    public $total_module;
    /** @var int */
    public $visit_deep;
    /** @var int */
    public $jumpout_module;
    /** @var int */
    public $current_module;
    /** @var int */
    public $module_duration;

    public function __construct(array $attributes,$config = [])
    {
        $this->setAttributes($attributes);
        parent::__construct($config);
    }


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['token'], 'string', 'max' => 128],
            [['current_page' . 'duration', 'total_page', 'total_module', 'visit_deep', 'jumpout_module', 'current_module', 'module_duration'], 'integer', 'max' => 11],
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
            'token'           => 'token',
            'current_page'    => '当前页码',
            'duration'        => '当前页面访问时长（秒）',
            'total_page'      => '总页数',
            'total_module'    => '总模块数',
            'visit_deep'      => '访问模块深度',
            'jumpout_module'  => '离开模块深度',
            'current_module'  => '当前模块数',
            'module_duration' => '当前模块访问时长（秒）',
        ];
    }

}