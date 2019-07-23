<?php
declare(strict_types=1);

namespace app\modules\v2\oauth\domain\dto;

use yii\base\Model;

/**
 * Class AuthorizeResponseDto
 * @property string $auth_code
 * @property string $state
 * @package app\modules\v2\oauth\domain\dto
 */
class AuthorizeResponseDto extends Model
{
    /* @var string $auth_code */
    public $auth_code;
    /* @var string $state */
    public $state;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['auth_code'], 'required'],
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
            'auth_code' => '授权码',
            'state'     => '自定义参数',
        ];
    }
}