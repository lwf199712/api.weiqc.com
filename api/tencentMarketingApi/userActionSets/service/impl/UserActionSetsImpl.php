<?php

namespace app\api\tencentMarketingApi\userActionSets\service\impl;

use app\api\tencentMarketingApi\oauth\api\OauthApi;
use app\api\tencentMarketingApi\userActionSets\domain\dto\UserActionSetsAddRequestDto;
use app\api\tencentMarketingApi\userActionSets\domain\dto\UserActionSetsAddResponseDto;
use app\api\tencentMarketingApi\userActionSets\service\UserActionSetsService;
use app\common\client\ClientBaseService;
use app\common\exception\TencentMarketingApiException;
use app\common\utils\ArrayUtils;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * Interface UserActionSetsService
 *
 * @package app\modules\v1\conversion\service
 * @author: lirong
 */
class UserActionSetsImpl extends ClientBaseService implements UserActionSetsService
{

    /**
     * UserActionsImpl constructor.
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
     * 用户行为数据源 - 创建用户行为数据源
     *
     * @param string $accessToken
     * @param UserActionSetsAddRequestDto $userActionSetsAddRequestDto
     * @return UserActionSetsAddResponseDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(string $accessToken, UserActionSetsAddRequestDto $userActionSetsAddRequestDto): UserActionSetsAddResponseDto
    {
        try {
            $response = $this->client->request('POST', Yii::$app->params['api']['tencent_marketing_api']['base_url'] . Yii::$app->params['api']['tencent_marketing_api']['api']['user_action_sets']['add'], [
                'query' => [
                    'access_token' => $accessToken,
                    'timestamp'    => time(),
                    'nonce'        => uniqid('', false) . time(),
                ],
                'json'  => $userActionSetsAddRequestDto->attributes
            ]);
            $response = json_decode($response->getBody()->getContents(), false);
            if (($response->code ?? true) && (int)$response->code !== 0) {
                throw new TencentMarketingApiException('创建用户行为数据源,接口返回错误:' . $response->message, $response->code ?? 500);
            }
            $userActionSetsAddResponseDto = new UserActionSetsAddResponseDto;
            $userActionSetsAddResponseDto->user_action_set_id = $response->data->user_action_set_id ?? '';
            return $userActionSetsAddResponseDto;
        } catch (GuzzleException $e) {
            throw new TencentMarketingApiException($e->getMessage(), $e->getCode());
        }
    }
}
