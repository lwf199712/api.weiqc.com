<?php

namespace app\utils;

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
     * push list
     *
     * @param mixed $key
     * @param mixed $value
     * @author: lirong
     */
    public function pushList($key, $value): void
    {
        $this->redis->rpushx($key, $value);
    }
}