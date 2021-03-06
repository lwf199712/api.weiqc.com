<?php

namespace app\common\utils;

use Predis\Client;
use Yii;
use yii\base\BaseObject;

/**
 * Class WindowsUtils
 *
 * @property Client $redis
 * @package app\modules\v1\utils
 * @author: lirong
 */
class RedisUtils extends BaseObject
{
    /* @var Client */
    protected $redis;

    /**
     * RedisUtils constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->redis = new Client(Yii::$app->params['redis']['service']);
        parent::__construct($config);
    }

    /**
     * @return Client
     * @author: lirong
     */
    public function getRedis(): Client
    {
        return $this->redis;
    }
}