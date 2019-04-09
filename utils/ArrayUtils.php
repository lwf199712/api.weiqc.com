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
}
