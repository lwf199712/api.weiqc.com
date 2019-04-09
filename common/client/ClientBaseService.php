<?php

namespace app\common\client;

use GuzzleHttp\Client;

/**
 * Class ClientBaseService
 *
 * @property Client $client
 * @package app\common\client
 * @author: lirong
 */
abstract class ClientBaseService
{
    /**
     * Guzzle客户端
     *
     * @var Client $client
     * @author: lirong
     * @data: 2019-02-13
     */
    protected $client;

}