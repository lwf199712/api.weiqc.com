<?php

namespace app\api\tencentMarketingAPI\userActions\service\impl;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\common\client\ClientBaseService;
use app\common\exception\TencentMarketingApiException;
use app\common\utils\ArrayUtils;
use app\daemon\course\conversion\domain\dto\FalseUserActionsDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
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
                    'access_token' => Yii::$app->params['api']['tencent_marketing_api']['access_token'],
                    'timestamp'    => time(),
                    'nonce'        => uniqid('', false) . time(),
                ],
                'json'  => ArrayUtils::attributesAsMap($userActionsDto)
            ]);
            $response = json_decode($response->getBody()->getContents(), false);
            if (($response->code ?? true) && (int)$response->code !== 0) {
                throw new TencentMarketingApiException('上传用户行为数据失败,接口返回错误:' . $response->message, $response->code ?? 500);
            }
        } catch (GuzzleException $e) {
            throw new TencentMarketingApiException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 批量上传用户行为数据
     *
     * @param array $userActionsDtoList
     * @return array
     * @author: lirong
     */
    public function batchAdd(array $userActionsDtoList): array
    {
        //失败返回的数组
        $falseUserActionsDtoList = [];
        $falseUserActionsDtoBase = new FalseUserActionsDto();
        $userActionsDtoList = array_values($userActionsDtoList);
        $requests = function () use ($userActionsDtoList) {
            //创建多个请求
            foreach ($userActionsDtoList as $userActionsDto) {
                yield function () use ($userActionsDto) {
                    return $this->client->request('POST', Yii::$app->params['api']['tencent_marketing_api']['base_url'] . Yii::$app->params['api']['tencent_marketing_api']['api']['user_actions']['add'], [
                        'query' => [
                            'access_token' => Yii::$app->params['api']['tencent_marketing_api']['access_token'],
                            'timestamp'    => time(),
                            'nonce'        => uniqid('', false) . time(),
                        ],
                        'json'  => ArrayUtils::attributesAsMap($userActionsDto)
                    ]);
                };
            }
        };
        $config = [
            'concurrency' => 20, //并发请求数
            'fulfilled'   => static function ($response, $index) use ($userActionsDtoList, &$falseUserActionsDtoList, $falseUserActionsDtoBase) {
                /* @var $response Request */
                $contents = json_decode($response->getBody()->getContents(), true);
                if (($contents['code'] ?? true) && (int)$contents['code'] !== 0) {
                    $falseUserActionsDto = clone $falseUserActionsDtoBase;
                    $falseUserActionsDto->message = $contents['message'];
                    $falseUserActionsDto->userActionsDto = $userActionsDtoList[$index];
                    $falseUserActionsDtoList[] = $falseUserActionsDto;
                }
            },
            'rejected'    => static function ($reason, $index) use ($userActionsDtoList, &$falseUserActionsDtoList, $falseUserActionsDtoBase) {
                $falseUserActionsDto = clone $falseUserActionsDtoBase;
                $falseUserActionsDto->message = $reason;
                $falseUserActionsDto->userActionsDto = $userActionsDtoList[$index];
                $falseUserActionsDtoList[] = $falseUserActionsDto;
            },
        ];
        (new Pool($this->client, $requests(), $config))->promise()->wait();
        return $falseUserActionsDtoList;
    }
}
