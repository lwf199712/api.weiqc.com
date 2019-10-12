<?php declare(strict_types=1);


namespace app\api\uacApi\service\impl;


use app\api\uacApi\dto\UserInfoDto;
use app\api\uacApi\service\User;
use app\common\client\ClientBaseService;
use app\common\exception\UacApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

class UserImpl extends ClientBaseService implements User
{

    public function __construct($config = [])
    {
        $this->client = new Client([
            'cookies'  => true,
            'timeout'  => 300,
            'base_uri' => Yii::$app->params['api']['uac_api']['base_url'],
        ]);
        parent::__construct($config);
    }

    /**
     * 获取用户详情
     * @param string $accessToken
     * @return UserInfoDto
     * @throws GuzzleException
     * @throws UacApiException
     */
    public function getUserInfo(string $accessToken) : UserInfoDto
    {
        $userInfo = new UserInfoDto;
        $response         = $this->client->request('GET', Yii::$app->params['api']['uac_api']['api']['user'], [
            'headers' => [
                'Authorization' => 'Bearer '.$accessToken
            ]
        ]);
        $response         = json_decode($response->getBody()->getContents(), true);
        if ($response['code'] !== 0){
            throw new UacApiException(UacApiException::defaultMessage($response));
        }
        $userInfo->setAttributes($response['data']);

        return $userInfo;


    }
}