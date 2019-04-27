<?php

namespace app\common\utils;

/**
 * Class ArrayUtils
 *
 * @package app\common\utils
 * @author: lirong
 */
class UrlUtils
{
    /**
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
}
