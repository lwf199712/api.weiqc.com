<?php

namespace app\daemon\course\conversion\domain\dto;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsRequestDto;
use yii\base\Model;

/**
 * Class UserActionsActionsRequestDto
 *
 * @property string $message
 * @property UserActionsRequestDto $userActionsDto
 * @package app\daemon\course\conversion\domain\dto
 * @author: lirong
 */
class FalseUserActionsDto extends Model
{
    /* @var string $message */
    public $message;
    /* @var UserActionsRequestDto $userActionsDto */
    public $userActionsDto;

    public function __construct($config = [])
    {
        $this->userActionsDto = new UserActionsRequestDto;
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
