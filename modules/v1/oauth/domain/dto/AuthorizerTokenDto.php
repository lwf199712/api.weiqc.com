<?php

namespace app\modules\v1\oauth\domain\vo;

use yii\base\Model;

/**
 * 通过 Authorization Code 获取 Access Token 或刷新 Access Token
 * Class AuthorizationTokenDto
 *
 * @property integer $client_id 应用 id，在开发者官网创建应用后获得，可通过 [应用程序管理页面] 查看
 * @property string $client_secret 应用 secret，在开发者官网创建应用后获得，可通过 [应用程序管理页面] 查看
 * @property string $grant_type 请求的类型，可选值： authorization_code （授权码方式获取 token ）、 refresh_token （刷新 token ）
 * @property string $authorization_code OAuth 认证 code，可通过获取 Authorization Code 接口获取，当 grant_type=authorization_code 时必填
 * @property string $refresh_token 应用 refresh token，当 grant_type=refresh_token 时必填
 * @property string $redirect_uri 应用回调地址，当 grant_type=authorization_code 时， redirect_uri 为必传参数，仅支持 http 和 https，不支持指定端口号，且传入的地址需要与获取 authorization_code 时，传入的回调地址保持一致
 * @package app\modules\v1\oauth\domain\vo
 * @author: lirong
 */
class AuthorizerTokenDto extends Model
{
    /* @var integer $client_id */
    public $client_id;
    /* @var string $client_secret */
    public $client_secret;
    /* @var string $grant_type */
    public $grant_type;
    /* @var string $authorization_code */
    public $authorization_code;
    /* @var string $refresh_token */
    public $refresh_token;
    /* @var string $redirect_uri */
    public $redirect_uri;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['client_id'], 'integer'],
            [['client_secret', 'refresh_token'], 'string', 'min' => 1, 'max' => 256],
            [['grant_type', 'authorization_code'], 'string', 'min' => 0, 'max' => 64],
            [['redirect_uri'], 'string', 'min' => 1, 'max' => 1024]
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
            'client_id'          => '应用 id',
            'client_secret'      => '应用 secret',
            'grant_type'         => '请求的类型',
            'authorization_code' => 'OAuth 认证 code',
            'refresh_token'      => '应用 refresh token',
            'redirect_uri'       => '应用回调地址',
        ];
    }
}
