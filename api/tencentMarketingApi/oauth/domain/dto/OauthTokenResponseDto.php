<?php

namespace app\api\tencentMarketingApi\oauth\domain\dto;

use yii\base\Model;

/**
 * Class OauthTokenResponseDto
 *
 * @property OauthTokenAuthorizerInfoResponseDto $authorizer_info 权限信息，当 grant_type=refresh_token 时不返回
 * @property string $access_token 应用 access token
 * @property string $refresh_token 应用 refresh token，当 grant_type=refresh_token 时不返回
 * @property integer $access_token_expires_in access_token 过期时间，单位（秒）
 * @property integer $refresh_token_expires_in refresh_token 过期时间，单位（秒），当 grant_type=refresh_token 时不返回
 * @package app\api\tencentMarketingApi\oauth\domain\dto
 * @author: lirong
 */
class OauthTokenResponseDto extends Model
{
    /* @var OauthTokenAuthorizerInfoResponseDto $authorizer_info */
    public $authorizer_info;
    /* @var string $access_token */
    public $access_token;
    /* @var string $refresh_token */
    public $refresh_token;
    /* @var integer $access_token_expires_in */
    public $access_token_expires_in;
    /* @var integer $refresh_token_expires_in */
    public $refresh_token_expires_in;

    public function __construct($config = [])
    {
        $this->authorizer_info = new OauthTokenAuthorizerInfoResponseDto();
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
            [['create_at', 'authorization_info', 'access_token', 'refresh_token', 'access_token_expires_in', 'refresh_token_expires_in'], 'safe'],
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
            'authorization_info'       => '权限信息',
            'access_token'             => '应用 access token',
            'refresh_token'            => '应用 refresh token',
            'access_token_expires_in'  => 'access_token 过期时间',
            'refresh_token_expires_in' => 'refresh_token 过期时间',
        ];
    }
}
