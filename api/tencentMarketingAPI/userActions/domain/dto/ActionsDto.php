<?php

namespace app\api\tencentMarketingApi\userActions\domain\dto;

use app\api\tencentMarketingApi\userActions\enum\ActionTypeEnum;
use yii\base\Model;

/**
 * Class ActionsDto
 *
 * @property integer $user_action_set_id 用户行为源 id
 * @property string $url 请求所在url
 * @property integer $action_time 行为发生时，客户端的时间点。 UNIX 时间，单位为秒，如果不填将使用服务端时间填写 最小值 0，最大值 2147483647
 * @property string|ActionTypeEnum $action_type 预定义的行为类型
 * @property TraceDto $trace 跟踪信息
 * @property array $action_param 行为所带的参数
 * @property string $outer_action_id 字段长度最小 1 字节，长度最大 204800 字节。是去重标识，平台会基于user_action_set_id，outer_action_id 和action_type三个字段做去重 ，如果历史上报数据中存在某条数据的这三个字段与当前上报数据完全一样的，则当前数据会被过滤掉
 *
 * @package app\api\tencentMarketingApi\domain\dto
 * @author: lirong
 */
class ActionsDto extends Model
{
    /* @var integer $user_action_set_id */
    public $user_action_set_id;
    /* @var string $url */
    public $url;
    /* @var integer $action_time */
    public $action_time;
    /* @var string|ActionTypeEnum|*enum $action_type */
    public $action_type;
    /* @var TraceDto $trace */
    public $trace;
    /* @var array $action_param */
    public $action_param;
    /* @var string $outer_action_id */
    public $outer_action_id;

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'user_action_set_id' => '用户行为源 id',
            'url'                => '请求所在url',
            'action_time'        => '客户端的时间点',
            'action_type'        => '预定义的行为类型',
            'trace'              => '跟踪信息',
            'action_param'       => '行为所带的参数',
            'outer_action_id'    => '去重标识',
        ];
    }
}
