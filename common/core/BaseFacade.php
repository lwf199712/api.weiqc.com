<?php
declare(strict_types=1);

namespace app\common\core;

use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;

abstract class BaseFacade
{
    /**
     *  @return object|string|array|null
     */
    protected static function getFacadeAccessor()
    {
        throw new InvalidArgumentException('Facade does not implement getFacadeAccessor method');
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws InvalidConfigException
     * @author zhuozhen
     */
    public static function __callStatic($name, $arguments)
    {
        $service = Instance::ensure(static::getFacadeAccessor());
        return call_user_func_array([$service,$name],$arguments);
    }
}