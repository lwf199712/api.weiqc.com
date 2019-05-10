<?php

namespace app\common\utils;

use Yii;
use yii\base\BaseObject;

/**
 * Class SourceDetectionUtil
 *
 * @property StringUtil $stringUtil
 * @package app\modules\v1\utils
 * @author: lirong
 */
class SourceDetectionUtil extends BaseObject
{
    /* @var StringUtil */
    protected $stringUtil;

    public function __construct(StringUtil $stringUtil, $config = [])
    {
        $this->stringUtil = $stringUtil;
        parent::__construct($config);
    }

    /**
     * Source url detection
     * If a domain name exists in the group, then Cross-domain
     *
     * @author: lirong
     */
    public function crossDomainDetection(): void
    {
        $httpOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($httpOrigin) {
            $httpOrigin = $this->stringUtil::cutOutLater($httpOrigin, '://');
            $httpOrigin = $this->stringUtil::cutOutFormer($httpOrigin, ':/');
            if (in_array($httpOrigin, Yii::$app->params['cross_domain'], false)) {
                header('Access-Control-Allow-Origin:' . $httpOrigin);
                header('Access-Control-Allow-Headers:' . $httpOrigin);
            }
        }
    }
}
