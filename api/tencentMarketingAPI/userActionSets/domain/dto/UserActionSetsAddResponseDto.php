<?php

namespace app\api\tencentMarketingApi\userActionSets\domain\dto;

use yii\base\Model;

/**
 * Class UserActionsTraceRequestDto
 *
 * @property integer $user_action_set_id 用户行为源 id，通过 [user_action_sets 接口] 创建用户行为源时分配的唯一 id
 * @package app\api\tencentMarketingApi\domain\dto
 * @author: lirong
 */
class UserActionSetsAddResponseDto extends Model
{
    /* @var string $user_action_set_id */
    public $user_action_set_id;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['user_action_set_id'], 'integer'],
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
            'user_action_set_id' => '用户行为源 id',
        ];
    }
}
