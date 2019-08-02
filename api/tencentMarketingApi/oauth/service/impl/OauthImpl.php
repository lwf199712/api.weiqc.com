<?php

namespace app\api\tencentMarketingApi\oauth\service\impl;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenAuthorizerInfoResponseDto;
use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenRequestDto;
use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenResponseDto;
use app\api\tencentMarketingApi\oauth\service\OauthService;
use app\common\client\ClientBaseService;
use app\common\exception\TencentMarketingApiException;
use app\common\utils\RedisUtils;
use app\models\dataObject\StaticConversionDo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * Interface ConversionService
 *
 * @property RedisUtils $redisUtils
 * @property StaticConversionDo $staticConversion
 * @author: lirong
 */
class OauthImpl extends ClientBaseService implements OauthService
{
    /**
     * OauthImpl constructor
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->client = new Client([
            'cookies'  => true,
            'timeout'  => 300,
            'base_uri' => Yii::$app->params['api']['tencent_marketing_api']['base_url']
        ]);
        parent::__construct($config);
    }

    /**
     * 通过 Authorization Code 获取 Access Token 或刷新 Access Token
     *
     * @param OauthTokenRequestDto $authorizationTokenDto
     * @return OauthTokenResponseDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function authorizeToken(OauthTokenRequestDto $authorizationTokenDto): OauthTokenResponseDto
    {
        $oauthDto = new OauthTokenResponseDto();
        try {
            $response = $this->client->request('GET', Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['token_url'], [
                'query' => $authorizationTokenDto->attributes,
            ]);
            $response = json_decode($response->getBody()->getContents(), false);
            if (($response->code ?? true) && (int)$response->code !== 0) {
                throw new TencentMarketingApiException('获取 Access Token数据失败,接口返回错误:' . $response->message, $response->code ?? 500);
            }
            $oauthDto->access_token = $response->data->access_token ?? '';
            $oauthDto->refresh_token = $response->data->refresh_token ?? '';
            $oauthDto->access_token_expires_in = ($response->data->access_token_expires_in ?? 0) + time();
            $oauthDto->refresh_token_expires_in = ($response->data->refresh_token_expires_in ?? 0) + time();
            $oauthDto->authorizer_info->account_uin = $response->data->authorizer_info->account_uin ?? '';
            $oauthDto->authorizer_info->account_id = $response->data->authorizer_info->account_id ?? '';
            $oauthDto->authorizer_info->scope_list = $response->data->authorizer_info->scope_list ?? '';
        } catch (GuzzleException $e) {
            throw new TencentMarketingApiException($e->getMessage(), $e->getCode());
        }
        return $oauthDto;
    }

}
