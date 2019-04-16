<?php

namespace app\utils;

use yii\base\Model;

/**
 * Class ArrayUtils
 *
 * @package app\utils
 * @author: lirong
 */
class ArrayUtils
{
    /**
     * attributes as map
     *
     * @param Model $model
     * @return array
     * @author: lirong
     */
    public static function attributesAsMap(Model $model): array
    {
        foreach ($model->attributes as $attributeName => $attribute) {
            if (is_array($attribute)) {
                $model->$attributeName = self::attributesMapAsMap($attribute);
            }
            if ($attribute instanceof Model) {
                $model->$attributeName = self::attributesAsMap($attribute);
            }
        }
        return $model->attributes;
    }

    /**
     * attributesList as map
     *
     * @param array $modelList
     * @return array
     * @author: lirong
     */
    public static function attributesMapAsMap(array $modelList): array
    {
        foreach ($modelList as &$model) {
            if ($model instanceof Model) {
                $model = self::attributesAsMap($model);
            }
        }
        return $modelList;
    }


    /**
     * 二位数组唯一性处理
     *
     * @param array $arrayList 二维数组
     * @param array $whereList ['name','age'] 当其名字和年龄相同时删除后面找到的一个值
     * @return array
     * @author: lirong
     */
    public function uniqueArrayDelete(array $arrayList, array $whereList): array
    {
        $arrayList = array_reverse($arrayList);
        foreach ($arrayList as $key => $array) {
            foreach ($arrayList as $keyFind => $arrayFind) {
                if ($key !== $keyFind) {
                    $isUnique = false;
                    foreach ($whereList as $where) {
                        if ((string)$array[$where] !== (string)$arrayFind[$where]) {
                            $isUnique = true;
                        }
                    }
                    if ($isUnique === false) {
                        unset($arrayList[$key]);
                    }
                }
            }
        }
        return array_reverse($arrayList);
    }

    /**
     * 查找二维数组是否存在指定数据
     *
     * @param array $arrayList
     * @param array $whereList
     * @return bool
     * @author: lirong
     */
    public function arrayExists(array $arrayList, array $whereList): bool
    {
        if ($arrayList) {
            foreach ($arrayList as $array) {
                $isExists = true;
                foreach ($whereList as $where => $value) {
                    if ((string)$array[$where] !== (string)$value) {
                        $isExists = false;
                    }
                }
                if ($isExists === true) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 在一个数组中查找一个数据
     *
     * @param array $arrayList
     * @param array $whereList
     * @return bool|mixed
     * @author: lirong
     */
    public function findOne(array $arrayList, array $whereList)
    {
        if ($arrayList) {
            foreach ($arrayList as $array) {
                $isExists = true;
                foreach ($whereList as $where => $value) {
                    if ((string)$array[$where] !== (string)$value) {
                        $isExists = false;
                    }
                }
                if ($isExists === true) {
                    return $array;
                }
            }
        }
        return false;
    }
}
