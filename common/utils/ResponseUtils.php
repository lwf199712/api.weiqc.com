<?php

namespace app\common\utils;

/**
 * Class ResponseUtils
 *
 * @package app\modules\v1\utils
 * @author: lirong
 */
class ResponseUtils
{
    /**
     * ip to int
     *
     * @param string $ip
     * @return int
     * @author: lirong
     */
    public function ipToInt(string $ip): int
    {
        [$ip1, $ip2, $ip3, $ip4] = explode('.', $ip);
        return ($ip1 << 24) | ($ip2 << 16) | ($ip3 << 8) | $ip4;
    }

    /**
     * Unified interface response
     *
     * @param bool $status Interface response status (success: true, failure: false)
     * @param string $message Interface response message
     * @param int $code Response code
     * @param array $data Response data
     * @return array
     * @author lirong
     */
    public function UnifyResponse(bool $status, string $message, int $code, array $data = []): array
    {
        return [
            'status'  => $status,
            'message' => $message,
            'code'    => $code,
            'data'    => $data
        ];
    }
}
