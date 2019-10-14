<?php declare(strict_types=1);

namespace app\api\uacApi\service\impl;

use app\api\uacApi\dto\TokenRequestDto;
use app\api\uacApi\dto\TokenResponseDto;
use app\api\uacApi\service\Oauth;
use app\common\client\ClientBaseService;
use app\common\exception\UacApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

class OauthImpl extends ClientBaseService implements Oauth
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
     * 申请token并验证登录
     * @param TokenRequestDto $tokenRequestDto
     * @return TokenResponseDto
     * @throws UacApiException
     * @throws GuzzleException
     */
    public function applyToken(TokenRequestDto $tokenRequestDto): TokenResponseDto
    {
        $tokenResponseDto = new TokenResponseDto;
        $response         = $this->client->request('POST', Yii::$app->params['api']['uac_api']['api']['access_token'], [
            'json' => $tokenRequestDto->getAttributes(),
        ]);
        $response         = json_decode($response->getBody()->getContents(), true);
        $tokenResponseDto->setAttributes($response);
        if ($tokenResponseDto->validate() === false) {
            throw new UacApiException(UacApiException::oauthMessage());
        }
        return $tokenResponseDto;
    }
}