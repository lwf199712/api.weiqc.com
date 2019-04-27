<?php

namespace app\common\client;

use GuzzleHttp\Client;
use yii\base\BaseObject;

/**
 * Class ClientBaseService
 *
 * @property Client $client
 * @package app\common\client
 * @author: lirong
 */
abstract class ClientBaseService extends BaseObject
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