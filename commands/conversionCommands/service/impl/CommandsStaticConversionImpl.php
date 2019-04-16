<?php

namespace app\commands\conversionCommands\service\impl;

use app\commands\conversionCommands\service\CommandsStaticConversionService;
use app\models\po\StaticConversionPo;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticConversionPo $staticConversion
 * @author: lirong
 */
class CommandsStaticConversionImpl extends BaseObject implements CommandsStaticConversionService
{

    /**
     * @param $condition
     * @return array
     * @author: lirong
     */
    public function findAll($condition): array
    {
        // TODO: Implement findAll() method.
    }
}
