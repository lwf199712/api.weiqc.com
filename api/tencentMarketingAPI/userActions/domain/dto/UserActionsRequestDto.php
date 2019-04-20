<?php

namespace app\api\tencentMarketingApi\userActions\domain\dto;

use yii\base\Model;

/**
 * Class UserActionsRequestDto
 *
 * @property integer $account_id 推广帐号 id，有操作权限的帐号 id，包括代理商和广告主帐号 id
 * @property integer $user_action_set_id 用户行为源 id，通过 [user_action_sets 接口] 创建用户行为源时分配的唯一 id
 * @property UserActionsActionsRequestDto|array $actions 转化行为
 * @package app\modules\v1\domain\vo
 * @author: lirong
 */
class UserActionsRequestDto extends Model
{
    /* @var integer $account_id */
    public $account_id;
    /* @var UserActionsActionsRequestDto|array $actions */
    public $actions;

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'account_id'         => '推广帐号id',
            'user_action_set_id' => '用户行为源id',
            'actions'            => '转化行为',
        ];
    }
}
