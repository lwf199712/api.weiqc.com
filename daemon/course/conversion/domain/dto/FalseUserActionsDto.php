<?php

namespace app\daemon\course\conversion\domain\dto;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use yii\base\Model;

/**
 * Class UserActionsDto
 *
 * @property string $message
 * @property UserActionsDto $userActionsDto
 * @package app\daemon\course\conversion\domain\dto
 * @author: lirong
 */
class FalseUserActionsDto extends Model
{
    /* @var string $message */
    public $message;
    /* @var UserActionsDto $userActionsDto */
    public $userActionsDto;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['message', 'userActionsDto'], 'safe']
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
            'message'        => 'message',
            'userActionsDto' => 'userActionsDto',
        ];
    }
}
