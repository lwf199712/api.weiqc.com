<?php

namespace app\api\tencentMarketingAPI\userActions\service\impl;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\common\client\ClientBaseService;
use app\common\exception\TencentMarketingApiException;
use app\utils\ArrayUtils;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * Interface UserActionsImpl
 *
 * @package app\api\tencentMarketingAPI\userActions\service\impl
 * @author: lirong
 */
class UserActionsImpl extends ClientBaseService implements UserActionsService
{
    /**
     * UserActionsImpl constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'cookies'  => true,
            'timeout'  => 300,
            'base_uri' => Yii::$app->params['api']['tencent_marketing_api']['base_url']
        ]);
    }

    /**
     * 上传用户行为数据
     *
     * @param UserActionsDto $userActionsDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(UserActionsDto $userActionsDto): void
    {
        try {
            $response = $this->client->request('POST', Yii::$app->params['api']['tencent_marketing_api']['base_url'] . Yii::$app->params['api']['tencent_marketing_api']['api']['user_actions']['add'], [
                'query' => [
                    'access_token'     => Yii::$app->params['api']['tencent_marketing_api']['access_token'],
                    'timestamp' => time(),
                    'nonce'     => uniqid('', false) . time(),
                ],
                'json'  => ArrayUtils::attributesAsMap($userActionsDto)
            ]);
            $response = json_decode($response->getBody()->getContents());
            if (($response->code ?? true) && (int)$response->code !== 0) {
                throw new TencentMarketingApiException('上传用户行为数据失败,接口返回错误:' . $response->message, $response->code ?? 500);
            }
        } catch (GuzzleException $e) {
            throw new TencentMarketingApiException($e->getMessage(), $e->getCode());
        }
    }
}
