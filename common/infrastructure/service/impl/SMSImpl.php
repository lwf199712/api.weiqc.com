<?php
declare(strict_types=1);

namespace app\common\infrastructure\service\impl;

use app\common\client\ClientBaseService;
use app\common\exception\YuanpianApiException;
use app\common\infrastructure\dto\MessageBundleDto;
use app\common\infrastructure\dto\SingleMessageDto;
use app\common\infrastructure\service\SMS;
use app\common\utils\ArrayUtils;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

class SMSImpl extends ClientBaseService implements SMS
{
    /**
     * SMSClient constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->client = new Client([
            'cookies'  => true,
            'timeout'  => 30,
            'base_uri' => Yii::$app->params['api']['yunpian_api']['base_url'],
            'headers'  => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        parent::__construct($config);
    }

    /**
     * 发送单条短信
     * @param SingleMessageDto $singleMessageDto
     * @throws YuanpianApiException
     * @throws GuzzleException
     * @author zhuozhen
     */
    public function singleSendMsg(SingleMessageDto $singleMessageDto): void
    {
        $response = $this->client->request('POST', Yii::$app->params['api']['yunpian_api']['sms_actions']['single_send_uri'], [
            'form_params' => ArrayUtils::attributesAsMap($singleMessageDto),
        ]);
        $response = json_decode($response->getBody()->getContents(), false);
        if ((int)$response->code !== 0) {
            throw new YuanpianApiException('短信发送失败,接口返回错误:' . $response->msg, $response->code ?? 500);
        }

    }


    /**
     * 批量发送短信
     * @param MessageBundleDto $messageBundleDto
     * @throws GuzzleException
     * @throws YuanpianApiException
     * @author zhuozhen
     */
    public function batchSendMsg(MessageBundleDto $messageBundleDto): void
    {
        $response = $this->client->request('POST', Yii::$app->params['api']['yunpian_api']['sms_actions']['single_send_uri'], [
            'form_params' => ArrayUtils::attributesAsMap($messageBundleDto),
        ]);
        $response = json_decode($response->getBody()->getContents(), false);
        if ((int)$response->code !== 0) {
            throw new YuanpianApiException('短信发送失败,接口返回错误:' . $response->msg, $response->code ?? 500);
        }
    }
}