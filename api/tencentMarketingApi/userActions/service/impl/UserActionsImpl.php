<?php

namespace app\api\tencentMarketingApi\userActions\service\impl;

use app\api\tencentMarketingApi\oauth\api\OauthApi;
use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenResponseDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsActionsRequestDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsRequestDto;
use app\api\tencentMarketingApi\userActions\service\UserActionsService;
use app\common\client\ClientBaseService;
use app\common\exception\TencentMarketingApiException;
use app\common\utils\ArrayUtils;
use app\daemon\course\conversion\domain\dto\FalseUserActionsDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\db\Exception;

/**
 * Interface UserActionsImpl
 *
 * @property OauthApi $oauthApi
 * @package app\api\tencentMarketingApi\userActions\service\impl
 * @author: lirong
 */
class UserActionsImpl extends ClientBaseService implements UserActionsService
{
    /* @var OauthApi */
    private $oauthApi;

    /**
     * UserActionsImpl constructor.
     *
     * @param OauthApi $oauthApi
     * @param array $config
     */
    public function __construct(OauthApi $oauthApi, $config = [])
    {
        $this->oauthApi = $oauthApi;
        $this->client   = new Client([
            'cookies'  => true,
            'timeout'  => 300,
            'base_uri' => Yii::$app->params['api']['tencent_marketing_api']['base_url'],
        ]);
        parent::__construct($config);
    }


    /**
     * 上传用户行为数据
     *
     * @param UserActionsRequestDto $userActionsRequestDto
     * @throws TencentMarketingApiException
     * @throws Exception
     * @author: lirong
     */
    public function add(UserActionsRequestDto $userActionsRequestDto): void
    {
        $oauthTokenResponseDto             = $this->oauthApi->getToken($userActionsRequestDto->account_uin);
        $userActionsRequestDto->account_id = $oauthTokenResponseDto->authorizer_info->account_id;
        Yii::info(json_encode(ArrayUtils::attributesAsMap(clone $userActionsRequestDto), 256), 'post_api');
        Yii::info(json_encode(Yii::$app->request->get(), 256), 'get_params');
        try {
            $response = $this->client->request('POST', Yii::$app->params['api']['tencent_marketing_api']['base_url'] . Yii::$app->params['api']['tencent_marketing_api']['api']['user_actions']['add'], [
                'query' => [
                    'access_token' => $oauthTokenResponseDto->access_token,
                    'timestamp'    => time(),
                    'nonce'        => uniqid('', false) . time(),
                ],
                'json'  => ArrayUtils::attributesAsMap($userActionsRequestDto),
            ]);
            $response = json_decode($response->getBody()->getContents(), false);
            if (($response->code ?? true) && (int) $response->code !== 0) {
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
        $userActionsDtoList      = array_values($userActionsDtoList);
        $requests                = function () use ($userActionsDtoList) {
            //创建多个请求
            foreach ($userActionsDtoList as $userActionsDto) {
                yield function () use ($userActionsDto) {
                    /* @var $userActionsDto UserActionsRequestDto */
                    $oauthTokenResponseDto      = $this->oauthApi->getToken($userActionsDto->account_uin);
                    $userActionsDto->account_id = $oauthTokenResponseDto->authorizer_info->account_id;
                    return $this->client->request('POST', Yii::$app->params['api']['tencent_marketing_api']['base_url'] . Yii::$app->params['api']['tencent_marketing_api']['api']['user_actions']['add'], [
                        'query' => [
                            'access_token' => $oauthTokenResponseDto->access_token,
                            'timestamp'    => time(),
                            'nonce'        => uniqid('', false) . time(),
                        ],
                        'json'  => ArrayUtils::attributesAsMap(clone $userActionsDto),
                    ]);
                };
            }
        };
        $config                  = [
            'concurrency' => 20, //并发请求数
            'fulfilled'   => static function ($response, $index) use ($userActionsDtoList, &$falseUserActionsDtoList, $falseUserActionsDtoBase) {
                /* @var $response Request */
                $contents = json_decode($response->getBody()->getContents(), true);
                if (($contents['code'] ?? true) && (int) $contents['code'] !== 0) {
                    /* @var $userActionsDto UserActionsRequestDto */
                    $userActionsDto                               = $userActionsDtoList[$index];
                    $falseUserActionsDto                          = clone $falseUserActionsDtoBase;
                    $falseUserActionsDto->message                 = $contents['message'];
                    $falseUserActionsDto->userActionsDto          = $userActionsDto;
                    $falseUserActionsDto->userActionsDto->actions = current($falseUserActionsDto->userActionsDto->actions);
                    $falseUserActionsDtoList[]                    = $falseUserActionsDto;
                }
            },
            'rejected'    => static function ($reason, $index) use ($userActionsDtoList, &$falseUserActionsDtoList, $falseUserActionsDtoBase) {
                /* @var $userActionsDto UserActionsRequestDto */
                $userActionsDto                               = $userActionsDtoList[$index];
                $falseUserActionsDto                          = clone $falseUserActionsDtoBase;
                $falseUserActionsDto->message                 = $reason;
                $falseUserActionsDto->userActionsDto          = $userActionsDto;
                $falseUserActionsDto->userActionsDto->actions = current($falseUserActionsDto->userActionsDto->actions);
                $falseUserActionsDtoList[]                    = $falseUserActionsDto;
            },
        ];
        (new Pool($this->client, $requests(), $config))->promise()->wait();
        return $falseUserActionsDtoList;
    }
}
