<?php
declare(strict_types=1);

namespace app\api\toutiaoMarketingApi\oauth\service;

use app\api\toutiaoMarketingApi\oauth\dto\TokenRequestDto;
use app\api\toutiaoMarketingApi\oauth\dto\TokenResponseDto;
use app\common\exception\ToutiaoMarketingApiException;
use GuzzleHttp\Exception\GuzzleException;

interface Oauth
{

    /**
     * 申请token
     * @param TokenRequestDto $tokenRequestDto
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws ToutiaoMarketingApiException
     * @author zhuozhen
     */
    public function applyToken(TokenRequestDto $tokenRequestDto) : TokenResponseDto;

    /**
     * 刷新token
     * @param TokenRequestDto $tokenRequestDto
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws ToutiaoMarketingApiException
     * @author zhuozhen
     */
    public function refreshToken(TokenRequestDto $tokenRequestDto) : TokenResponseDto;

    /**
     * 将token存进缓存中
     * @param TokenResponseDto $tokenResponseDto
     * @author zhuozhen
     */
    public function cacheToken(TokenResponseDto $tokenResponseDto) : void ;

    /**
     * 从缓存中获取token
     * @param int $advertiser_id
     * @return TokenResponseDto
     * @author zhuozhen
     */
    public function getToken(int $advertiser_id) : TokenResponseDto;

    /**
     * token过期时换取可用token
     * @param TokenResponseDto $tokenResponseDto
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws ToutiaoMarketingApiException
     * @author zhuozhen
     */
    public function availableToken(TokenResponseDto $tokenResponseDto): TokenResponseDto;


}