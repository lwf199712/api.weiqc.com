<?php declare(strict_types=1);


namespace app\common\exception;


use yii\base\Exception;

class UacApiException extends Exception
{


    public static function defaultMessage($response): string
    {
        return 'UAC接口报错: message:' . $response['msg'] . ' code :' . $response['code'];
    }

    public static function oauthMessage(): string
    {
        return 'UAC授权接口数据报错';
    }
}