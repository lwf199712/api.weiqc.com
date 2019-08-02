<?php
declare(strict_types=1);

namespace app\common\infrastructure\service;

use app\common\exception\YuanpianApiException;

use app\common\infrastructure\dto\MessageBundleDto;
use app\common\infrastructure\dto\SingleMessageDto;
use GuzzleHttp\Exception\GuzzleException;

interface SMS
{
    /**
     * 发送单条短信
     * @param SingleMessageDto $singleMessageDto
     * @throws YuanpianApiException
     * @throws GuzzleException
     * @author zhuozhen
     */
    public function singleSendMsg(SingleMessageDto $singleMessageDto) : void;

    /**
     * 批量发送短信
     * @param MessageBundleDto $messageBundleDto
     * @author zhuozhen
     */
    public function batchSendMsg(MessageBundleDto $messageBundleDto) : void ;

}