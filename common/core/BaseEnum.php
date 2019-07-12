<?php


namespace app\common\core;

use ReflectionClass;

/**
 * 注解式枚举类型
 *
 * Class BaseEnum
 * @package app\components\core
 */
abstract class BaseEnum
{
    public static function __callStatic($name, $arguments)
    {
        $name = self::doGet($name);
        try {
            $_doc = [];
            $class = new ReflectionClass(new static());
            $properties = $class->getReflectionConstants();
            foreach ($properties as $item) {
                $_doc[$name]  = self::parse($item->getDocComment(),$name);
            }
            return $_doc[$name];
        } catch (\ReflectionException $e) {
        }
    }

    /**
     * @param $doc
     * @param $name
     * @return string
     * @author zhuozhen
     */
    private static function parse($doc, $name) : string
    {
        $pattern = "/\@{$name}\(\'(.*)\'\)/U";
        if (preg_match($pattern, $doc, $result) && isset($result[1])) {
            return $result[1];
        }
    }

    /**
     * @param string $name
     * @return string
     * @author zhuozhen
     */
    private static function doGet(string $name) : string
    {
        return lcfirst(ltrim($name,'get'));
    }
}