<?php

namespace app\modules\v1\userAction\service\impl;

use app\common\exception\ValidateException;
use app\models\dataObject\StaticConversionDo;
use app\modules\v1\userAction\service\UserActionStaticConversionService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticConversionDo $staticConversion
 * @author: lirong
 */
class UserActionUserActionStaticConversionImpl extends BaseObject implements UserActionStaticConversionService
{
    /* @var StaticConversionDo */
    private $staticConversion;

    /**
     * UserActionUserActionStaticConversionImpl constructor.
     *
     * @param StaticConversionDo $StaticConversionPo
     * @param array $config
     */
    public function __construct(StaticConversionDo $StaticConversionPo, $config = [])
    {
        $this->staticConversion = $StaticConversionPo;
        parent::__construct($config);
    }

    /**
     * @param mixed $condition
     * @return StaticConversionDo|null|mixed
     * @author: lirong
     */
    public function findOne($condition)
    {
        return $this->staticConversion::findOne($condition);
    }

    /**
     * @param StaticConversionDo $staticConversionPo
     * @return int
     * @throws ValidateException
     * @author: lirong
     */
    public function insert($staticConversionPo): int
    {
        /* @var $staticConversion StaticConversionDo */
        $staticConversion = clone $this->staticConversion;
        $staticConversion->attributes = $staticConversionPo->attributes;
        if (!$staticConversion->save()) {
            throw new ValidateException($staticConversion, '表单参数校验异常！', 302);
        }
        return (int)$staticConversion->id;
    }

    /**
     * @param mixed $condition
     * @return boolean|null|mixed
     * @author: lirong
     */
    public function exists($condition)
    {
        return $this->staticConversion::find()->where($condition)->exists();
    }
}
