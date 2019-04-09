<?php

namespace app\api\tencentMarketingApi\userActions\domain\dto;

use app\api\tencentMarketingApi\userActions\enum\ActionTypeEnum;
use yii\base\Model;

/**
 * Class ActionsDto
 *
 * @property integer $action_time 行为发生时，客户端的时间点. UNIX 时间，单位为秒，如果不填将使用服务端时间填写 最小值 0，最大值 2147483647
 * @property UserId $user_id 用户标识
 * @property string|ActionTypeEnum|*enum $action_type 标准行为类型，当值为 'CUSTOM' 时表示自定义行为类型，
 * @property string $external_action_id 用户自定义的行为 id 标识字段长度最小 0 字节，长度最大 255 字节
 * @property string $action_param 行为所带的参数，详见 [param_map]字段长度最小 1 字节，长度最大 204800 字节
 * @property string $custom_action 自定义行为类型，当 action_type=CUSTOM 时必填字段长度最小 1 字节，长度最大 128 字节
 * @property TraceDto $trace 跟踪信息
 *
 * @package app\api\tencentMarketingApi\domain\dto
 * @author: lirong
 */
class ActionsDto extends Model implements ActionTypeEnum
{
    /* @var integer $action_time */
    public $action_time;
    /* @var UserId user_id */
    public $user_id;
    /* @var string|ActionTypeEnum|*enum $action_type */
    public $action_type;
    /* @var string $external_action_id */
    public $external_action_id;
    /* @var string $action_param */
    public $action_param;
    /* @var string $custom_action */
    public $custom_action;
    /* @var TraceDto $trace */
    public $trace;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['action_time'], 'integer'],
            [['action_type', 'external_action_id', 'custom_action'], 'string', 'max' => 128],
            [['action_param'], 'string', 'max' => 204800],
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
            'action_time'        => '行为发生时，客户端的时间点',
            'user_id'            => '用户标识',
            'action_type'        => '标准行为类型',
            'external_action_id' => '用户自定义的行为 id 标识',
            'action_param'       => '行为所带的参数',
            'custom_action'      => '自定义行为类型',
            'trace'              => '跟踪信息',
        ];
    }
}
