<?php

namespace app\modules\v1\oauth\domain\vo;

use yii\base\Model;

/**
 * Class TokenDto
 *
 * @property string $authorization_code OAuth 认证 code
 * @property string $state 验证请求有效性参数，值为用户自取，用于阻止跨站请求伪造攻击
 * @package app\modules\v1\oauth\domain\dto
 * @author: lirong
 */
class AuthorizeResponseVo extends Model
{
    /* @var string $authorization_code */
    public $authorization_code;
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
            [['authorization_code'], 'string', 'min' => 1, 'max' => 1024],
            [['state'], 'string', 'min' => 0, 'max' => 64],
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
            'authorization_code' => 'OAuth 认证 code',
            'state'              => '验证请求有效性参数',
        ];
    }
}
