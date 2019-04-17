<?php

namespace app\daemon\conversionCommands\service\impl;

use app\daemon\conversionCommands\service\CommandsStaticUrlService;
use app\models\po\StaticUrlPo;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticUrlPo $staticUrl
 * @author: lirong
 */
class CommandsStaticUrlImpl extends BaseObject implements CommandsStaticUrlService
{
    /* @var $staticUrl StaticUrlPo */
    private $staticUrl;

    /**
     * CommandsStaticConversionImpl constructor.
     *
     * @param StaticUrlPo $staticUrl
     * @param array $config
     */
    public function __construct(StaticUrlPo $staticUrl, $config = [])
    {
        $this->staticUrl = $staticUrl;
        parent::__construct($config);
    }

    /**
     * find all
     *
     * @param $condition
     * @return array
     * @author: lirong
     */
    public function findAll($condition): array
    {
        return $this->staticUrl::find()->where($condition)->all();
    }
}
