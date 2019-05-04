<?php

namespace app\api\tencentMarketingApi\userActions\domain\dto;

use yii\base\Model;

/**
 * Class UserActionsRequestDto
 *
 * @property integer $account_id 推广帐号 id，有操作权限的帐号 id，包括代理商和广告主帐号 id
 * @property integer $account_uin qq号码
 * @property integer $user_action_set_id 用户行为源 id，通过 [user_action_sets 接口] 创建用户行为源时分配的唯一 id
 * @property UserActionsActionsRequestDto|array $actions 转化行为
 * @package app\modules\v1\domain\vo
 * @author: lirong
 */
class UserActionsRequestDto extends Model
{
    /* @var integer $account_uin */
    public $account_uin;
    /* @var integer $account_id */
    public $account_id;
    /* @var UserActionsActionsRequestDto|array $actions */
    public $actions;

    public function __construct($config = [])
    {
        $this->actions = new UserActionsActionsRequestDto();
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
            [['account_uin', 'account_id', 'actions'], 'safe']
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
            'account_id'         => '推广帐号id',
            'user_action_set_id' => '用户行为源id',
            'actions'            => '转化行为',
        ];
    }
}
