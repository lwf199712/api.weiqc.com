<?php
declare(strict_types=1);

namespace app\api\toutiaoMarketingApi\oauth\service\impl;

use app\api\toutiaoMarketingApi\oauth\dto\TokenRequestDto;
use app\api\toutiaoMarketingApi\oauth\dto\TokenResponseDto;
use app\api\toutiaoMarketingApi\oauth\service\Oauth;
use app\common\client\ClientBaseService;
use app\common\exception\ToutiaoMarketingApiException;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\modules\v2\oauth\domain\enum\GrantTypeEnum;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * Class OauthImpl
 * @property RedisUtils $redisUtils
 * @property ArrayUtils $arrayUtils
 * @package app\api\toutiaoMarketingApi\oauth\service\impl
 */
class OauthImpl extends ClientBaseService implements Oauth
{
    /** @var RedisUtils */
    public $redisUtils;
    /** @var ArrayUtils */
    public $arrayUtils;

    public function __construct($config = [])
    {
        $this->client = new Client([
            'cookies'  => true,
            'timeout'  => 300,
            'base_uri' => Yii::$app->params['api']['toutiao_marketing_api']['base_url'],
        ]);
        parent::__construct($config);
    }

    /**
     * 申请token
     * @param TokenRequestDto $tokenRequestDto
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws ToutiaoMarketingApiException
     * @author zhuozhen
     */
    public function applyToken(TokenRequestDto $tokenRequestDto): TokenResponseDto
    {
        $tokenResponseDto = new TokenResponseDto();
        $response         = $this->client->request('POST', Yii::$app->params['api']['toutiao_marketing_api']['access_token'], [
            'json' => json_encode($tokenRequestDto->getAttributes()),
        ]);
        $response         = json_decode($response->getBody()->getContents(), false);
        $tokenResponseDto->setAttributes($response);
        if ($tokenResponseDto->validate() === false) {
            throw new ToutiaoMarketingApiException(ToutiaoMarketingApiException::defaultMessage($response));
        }
        $this->cacheToken($tokenResponseDto);
        return $tokenResponseDto;
    }


    /**
     * 刷新token
     * @param TokenRequestDto $tokenRequestDto
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws ToutiaoMarketingApiException
     * @author zhuozhen
     */
    public function refreshToken(TokenRequestDto $tokenRequestDto): TokenResponseDto
    {
        $tokenResponseDto = new TokenResponseDto();
        $response         = $this->client->request('POST', Yii::$app->params['api']['toutiao_marketing_api']['refresh_token'], [
            'json' => json_encode($tokenRequestDto->getAttributes()),
        ]);
        $response         = json_decode($response->getBody()->getContents(), false);
        $tokenResponseDto->setAttributes($response);
        if ($tokenResponseDto->validate() === false) {
            throw new ToutiaoMarketingApiException(ToutiaoMarketingApiException::defaultMessage($response));
        }
        $this->cacheToken($tokenResponseDto);
        return $tokenResponseDto;
    }

    /**
     * 将token存进缓存中
     * @param TokenResponseDto $tokenResponseDto
     * @author zhuozhen
     */
    public function cacheToken(TokenResponseDto $tokenResponseDto): void
    {
        $this->redisUtils->getRedis()->set(Yii::$app->params['oauth']['toutiao_marketing_api']['token_key'] . $tokenResponseDto->advertiser_id,
            json_encode($tokenResponseDto), $tokenResponseDto->expires_in
        );
    }

    /**
     * 从缓存中获取token
     * @param int $advertiser_id
     * @return TokenResponseDto
     * @author zhuozhen
     */
    public function getToken(int $advertiser_id): TokenResponseDto
    {
        $tokenResponseDto = new TokenResponseDto;
        $token            = $this->redisUtils->getRedis()->get(Yii::$app->params['oauth']['toutiao_marketing_api']['token_key'] . $advertiser_id);
        $tokenResponseDto->setAttributes($token);
        return $tokenResponseDto;
    }

    /**
     * token过期时换取可用token
     * @param TokenResponseDto $tokenResponseDto
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws ToutiaoMarketingApiException
     * @author zhuozhen
     */
    public function availableToken(TokenResponseDto $tokenResponseDto): TokenResponseDto
    {
        $token = $this->redisUtils->getRedis()->get(Yii::$app->params['oauth']['toutiao_marketing_api']['token_key'] . $tokenResponseDto->advertiser_id);
        if ($tokenResponseDto->access_token === json_decode($token, false)->access_token) {
            $tokenRequestDto                = new TokenRequestDto;
            $tokenRequestDto->app_id        = Yii::$app->params['oauth']['toutiao_marketing_api']['app_id'];
            $tokenRequestDto->secret        = Yii::$app->params['oauth']['toutiao_marketing_api']['secret'];
            $tokenRequestDto->grant_type    = GrantTypeEnum::REFRESH_TOKEN;
            $tokenRequestDto->refresh_token = $tokenResponseDto->refresh_token;
            return $this->refreshToken($tokenRequestDto);
        }

        return json_decode($token, false);
    }

}