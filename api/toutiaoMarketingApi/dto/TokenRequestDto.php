<?php
declare(strict_types=1);

namespace app\api\toutiaoMarketingApi\oauth\dto;

use yii\base\Model;

/**
 * Class TokenRequestDto
 * @property int    $app_id
 * @property string $secret
 * @property string $grant_type
 * @property string $auth_code
 * @property string $refresh_token
 *
 * @package app\api\toutiaoMarketingApi\oauth\dto
 */
class TokenRequestDto extends Model
{
    /* @var int app_id */
    public $app_id;
    /* @var string $secret */
    public $secret;
    /* @var string $grant_type */
    public $grant_type;
    /** @var string $auth_code */
    public $auth_code;
    /** @var string $refresh_token */
    public $refresh_token;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['app_id', 'secret', 'grant_type'], 'required'],
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
            'app_id'        => '接入方ID,今日头条提供',
            'secret'        => '接入方私钥,今日头条提供',
            'grant_type'    => '授权类型(auth_code,refresh_token)',
            'auth_code'     => '授权码，grant_type为auth_code时必填',
            'refresh_token' => '刷新用token，grant_type为refresh_token时必填',
        ];
    }
}