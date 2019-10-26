<?php declare(strict_types=1);

namespace app\api\uacApi\dto;


use yii\base\Model;

/**
 * Class TokenRequestDto
 * @property string $client_id
 * @property string $client_secret
 * @property string $grant_type
 * @property string $username
 * @property string password
 *
 * @package app\api\uacApi\dto
 */
class TokenRequestDto extends Model
{
    /** @var string */
    public $client_id;
    /** @var string */
    public $client_secret;
    /** @var string */
    public $grant_type;
    /** @var string */
    public $username;
    /** @var string */
    public $password;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['client_id', 'client_secret', 'grant_type', 'username', 'password'], 'required'],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels() : array
    {
        return [
            'client_id'     => '应用ID',
            'client_secret' => '应用secret',
            'grant_type'    => '授权类型',
            'username'      => '用户名',
            'password'      => '密码',
        ];
    }

}