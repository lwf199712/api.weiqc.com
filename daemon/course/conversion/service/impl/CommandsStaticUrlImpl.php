<?php

namespace app\daemon\course\conversion\service\impl;

use app\daemon\course\conversion\service\CommandsStaticUrlService;
use app\models\dataObject\StaticUrlDo;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticUrlDo $staticUrl
 * @author: lirong
 */
class CommandsStaticUrlImpl extends BaseObject implements CommandsStaticUrlService
{
    /* @var $staticUrl StaticUrlDo */
    private $staticUrl;

    /**
     * CommandsStaticConversionImpl constructor.
     *
     * @param StaticUrlDo $staticUrl
     * @param array $config
     */
    public function __construct(StaticUrlDo $staticUrl, $config = [])
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
