<?php

namespace app\modules\v1\utils;

use Yii;

/**
 * Class SourceDetectionUtil
 *
 * @package app\modules\v1\utils
 * @author: lirong
 */
class SourceDetectionUtil
{
    /* @var StringUtil */
    private static $stringUtil = StringUtil::class;

    /**
     * Source url detection
     * If a domain name exists in the group, then Cross-domain
     *
     * @author: lirong
     */
    public static function crossDomainDetection(): void
    {
        $httpOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($httpOrigin) {
            $httpOrigin = self::$stringUtil::cutOutLater($httpOrigin, '://');
            $httpOrigin = self::$stringUtil::cutOutFormer($httpOrigin, '/');
            if (in_array($httpOrigin, Yii::$app->params['cross_domain'], false)) {
                header('Access-Control-Allow-Origin:' . $httpOrigin);
            }
        }
    }
}
