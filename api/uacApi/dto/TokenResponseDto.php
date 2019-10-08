<?php declare(strict_types=1);


namespace app\api\uacApi\dto;


use yii\base\Model;

/**
 * Class TokenResponseDto
 *
 * @property string $token_type
 * @property int $expires_in
 * @property string $access_token
 * @property string $refresh_token
 *
 * @package app\api\uacApi\dto
 */
class TokenResponseDto extends Model
{

    /** @var string */
    public $token_type;
    /** @var int */
    public $expires_in;
    /** @var string */
    public $access_token;
    /** @var string */
    public $refresh_token;

    public function rules(): array
    {
        return [
            [['token_type', 'access_token', 'refresh_token'], 'string'],
            [['expires_in'], 'int'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'token_type'    => '令牌类型',
            'expires_in'    => '令牌有效时间(默认7天)	',
            'access_token'  => '访问令牌',
            'refresh_token' => '刷新令牌',

        ];
    }
}