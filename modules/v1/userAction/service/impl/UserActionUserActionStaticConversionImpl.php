<?php

namespace app\modules\v1\userAction\service\impl;

use app\common\exception\ValidateException;
use app\models\po\StaticConversionPo;
use app\modules\v1\userAction\service\UserActionStaticConversionService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticConversionPo $staticConversion
 * @author: lirong
 */
class UserActionUserActionStaticConversionImpl extends BaseObject implements UserActionStaticConversionService
{
    /* @var StaticConversionPo */
    private $staticConversion;

    /**
     * UserActionUserActionStaticConversionImpl constructor.
     *
     * @param StaticConversionPo $StaticConversionPo
     * @param array $config
     */
    public function __construct(StaticConversionPo $StaticConversionPo, $config = [])
    {
        $this->staticConversion = $StaticConversionPo;
        parent::__construct($config);
    }

    /**
     * @param mixed $condition
     * @return StaticConversionPo|null|mixed
     * @author: lirong
     */
    public function findOne($condition)
    {
        return $this->staticConversion::findOne($condition);
    }

    /**
     * @param StaticConversionPo $staticConversionPo
     * @return int
     * @throws ValidateException
     * @author: lirong
     */
    public function insert($staticConversionPo): int
    {
        /* @var $staticConversion StaticConversionPo */
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
