<?php
declare(strict_types=1);

namespace app\common\exception;

use yii\base\Exception;

class ToutiaoMarketingApiException extends Exception
{
    public static function defaultMessage($response) : string
    {
        return "'获取头条Token失败：'.$response->message,$response->code ?? 500";
    }
}