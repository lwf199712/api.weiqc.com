<?php

namespace app\daemon\course\conversion\domain\dto;

use yii\base\Model;

/**
 * Class RedisAddViewDto
 *
 * @property string $token token
 * @property integer $u_id
 * @property integer $ip IP地址
 * @property string $country 国家
 * @property string $area 区域
 * @property string $date 日期
 * @property string $url
 * @property string $referer 引荐
 * @property string $agent 代理人
 * @property integer $createtime 创建时间
 * @property string $account_uin
 * @property string $user_action_set_id
 * @property string $click_id
 * @property string $action_param
 * @property string $request_from_mobile
 * @property string $page
 * @package app\modules\v1\userAction\domain\dto
 * @author: lirong
 */
class RedisAddViewDto extends Model
{
    /* @var string $token */
    public $token;
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
    /* @var string $referer */
    public $referer;
    /* @var string $agent */
    public $agent;
    /* @var integer $createtime */
    public $createtime;
    /* @var integer $account_uin */
    public $account_uin;
    /* @var integer $user_action_set_id */
    public $user_action_set_id;
    /* @var string $click_id */
    public $click_id;
    /* @var string $action_param */
    public $action_param;
    /* @var string $url */
    public $url;
    /* @var string $request_from_mobile */
    public $request_from_mobile;
    /* @var string $page */
    public $page;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['page', 'request_from_mobile', 'token', 'u_id', 'ip', 'country', 'area', 'date', 'referer', 'agent', 'createtime', 'account_uin', 'user_action_set_id', 'click_id', 'action_param', 'url',], 'string']
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
            'token'               => 'token',
            'u_id'                => 'u_id',
            'ip'                  => 'ip',
            'country'             => 'country',
            'area'                => 'area',
            'date'                => 'date',
            'referer'             => 'referer',
            'agent'               => 'agent',
            'createtime'          => 'createtime',
            'account_uin'         => 'account_uin',
            'user_action_set_id'  => 'user_action_set_id',
            'click_id'            => 'click_id',
            'action_param'        => 'action_param',
            'url'                 => 'url',
            'request_from_mobile' => 'request_from_mobile',
        ];
    }
}
