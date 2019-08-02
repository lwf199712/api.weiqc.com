<?php

namespace app\common\utils;

use yii\base\InvalidConfigException;
use yii\web\Request;

/**
 * Class ArrayUtils
 *
 * @package app\common\utils
 * @author: lirong
 */
class UrlUtils
{
    /**
     * 拼接URL参数
     * @param array $params
     * @return string
     * @author: lirong
     */
    public function getRequestParamsFromGet(array $params): string
    {
        if ($params) {
            $getParams = '?';
            foreach ($params as $key => $param) {
                $getParams .= $key . '=' . $param . '&';
            }
            $getParamsLen = strlen($getParams) - 1;
            return substr($getParams, 0, $getParamsLen);
        }
        return '';
    }

    /**
     * 获取完整的URL
     * @param Request $request
     * @return string
     * @throws InvalidConfigException
     * @author zhuozhen
     */
    public function getClientUrl(Request $request) : string
    {
        $pageURL = ($request->getIsSecureConnection() ?  'http' : 'https' ) .'://';
         if ($request->securePort !== 80){
             return $pageURL . $request->getServerName() . ':' . $request->getServerPort() . $request->getUrl();
         }
         return $pageURL . $request->getServerName() . $request->getUrl();

    }
}
