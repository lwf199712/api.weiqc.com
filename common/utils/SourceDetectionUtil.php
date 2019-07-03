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
            $httpOrigin = $this->stringUtil::cutOutLater($httpOrigin, 'https://');
            $httpOrigin = $this->stringUtil::cutOutFormer($httpOrigin, 'http://');
            $httpOrigin = $this->stringUtil::cutOutFormer($httpOrigin, '.');
            if (in_array($httpOrigin, Yii::$app->params['cross_domain'], false)) {
                header('Access-Control-Allow-Origin:' . $httpOrigin);
                header('Access-Control-Allow-Headers:' . $httpOrigin);
            }
        }
    }

    /**
     * url device detection
     * return true if is mobile phone request
     * @return bool
     */
    public function mobileDetection(): bool
    {
        $mobileBrowserList = Yii::$app->params['mobile'];
        $useragent         = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (($this->dstrpos($useragent, $mobileBrowserList))) {
            return true;
        }

        $browser = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if($this->dstrpos($useragent, $browser)) {
            return false;
        }

//        if($_GET['mobile'] === 'yes') {
//            return true;
//        } else {
//            return false;
//        }
    }



    private function dstrpos($string, &$arr, $returnValue = false)
    {
        if (empty($string)) {
            return false;
        }
        foreach ((array)$arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnValue ? $v : true;
                return $return;
            }
        }
        return false;
    }
}
