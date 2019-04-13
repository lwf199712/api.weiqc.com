<?php

namespace app\modules\v1\userAction\service\impl;

use app\modules\v1\userAction\domain\po\StaticUrlPo;
use app\modules\v1\userAction\service\StaticUrlService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticUrlPo $staticUrl
 * @author: lirong
 */
class StaticUrlImpl extends BaseObject implements StaticUrlService
{
    /* @var StaticUrlPo */
    private $staticUrl;

    public function __construct(StaticUrlPo $staticUrl, $config = [])
    {
        $this->staticUrl = $staticUrl;
        parent::__construct($config);
    }

    /**
     * @param mixed $condition
     * @return StaticUrlPo|mixed|null
     * @author: lirong
     */
    public function findOne($condition)
    {
        return $this->staticUrl::findOne($condition);
    }
}
