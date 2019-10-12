<?php declare(strict_types=1);


namespace app\api\uacApi\service;


use app\api\uacApi\dto\UserInfoDto;
use app\common\exception\UacApiException;
use GuzzleHttp\Exception\GuzzleException;

interface User
{
    /**
     * 获取用户详情
     * @param string $accessToken
     * @return UserInfoDto
     * @throws GuzzleException
     * @throws UacApiException
     */
    public function getUserInfo(string $accessToken) : UserInfoDto;
}