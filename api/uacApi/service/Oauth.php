<?php declare(strict_types=1);

namespace app\api\uacApi\service;

use app\api\uacApi\dto\TokenRequestDto;
use app\api\uacApi\dto\TokenResponseDto;
use app\common\exception\UacApiException;
use GuzzleHttp\Exception\GuzzleException;

interface Oauth
{
    /**
     * 申请token并验证登录
     * @param TokenRequestDto $tokenRequestDto
     * @return TokenResponseDto
     * @throws UacApiException
     * @throws GuzzleException
     */
    public function applyToken(TokenRequestDto $tokenRequestDto) : TokenResponseDto;

}