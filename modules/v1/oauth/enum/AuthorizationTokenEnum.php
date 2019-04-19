<?php

namespace app\modules\v1\oauth\enum;

/**
 * 接口常量
 * https://api.e.qq.com/oauth/token
 * Class AuthorizeEnum
 *
 * @package app\modules\v1\oauth\enum
 * @author: lirong
 */
abstract class AuthorizationTokenEnum
{
    /**
     * 授权码方式获取 token
     *
     * @var string
     * @author lirong
     */
    public const AUTHORIZATION_CODE = 'authorization_code';
    /**
     * 刷新 token
     *
     * @var string
     * @author lirong
     */
    public const REFRESH_TOKEN = 'refresh_token';
}