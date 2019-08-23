<?php

namespace app\daemon\course\urlConvert\domain\dto;

use yii\base\Model;

/**
 * Class RedisAddViewDto
 *
 * @property integer $u_id
 * @property integer $ip IP地址
 * @property string $country 国家
 * @property string $area 区域
 * @property string $date 日期
 * @property string $url
 * @property string $referer 引荐
 * @property string $agent 代理人
 * @property integer $createtime 创建时间
 * @property string $page
 * @package app\modules\v1\userAction\domain\dto
 * @author: zhuozhen
 */
class RedisUrlConvertDto extends Model
{
    /* @var integer $u_id */
    public $u_id;
    /* @var integer $ip */
    public $ip;
    /* @var string $country */
    public $country;
    /* @var string $area */
    public $area;
    /* @var string $date */
    public $date;
    /** @var string $page */
    public $page;
    /* @var string $referer */
    public $referer;
    /* @var string $agent */
    public $agent;
    /* @var integer $createtime */
    public $createtime;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['u_id', 'ip', 'country', 'area', 'date', 'page','referer', 'agent', 'createtime',], 'string']
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
            'u_id'                => 'u_id',
            'ip'                  => 'ip',
            'country'             => 'country',
            'area'                => 'area',
            'page'                => 'page',
            'date'                => 'date',
            'referer'             => 'referer',
            'agent'               => 'agent',
            'createtime'          => 'createtime',
        ];
    }
}
