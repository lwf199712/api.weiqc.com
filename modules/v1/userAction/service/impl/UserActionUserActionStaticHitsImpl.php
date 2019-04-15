<?php

namespace app\modules\v1\userAction\service\impl;

use app\models\po\StaticHitsPo;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticHitsPo $staticHits
 * @author: lirong
 */
class UserActionUserActionStaticHitsImpl extends BaseObject implements UserActionStaticHitsService
{
    /* @var StaticHitsPo */
    private $staticHits;

    /**
     * UserActionUserActionStaticServiceConversionsImpl constructor.
     *
     * @param StaticHitsPo $staticHits
     * @param array $config
     */
    public function __construct(StaticHitsPo $staticHits, $config = [])
    {
        $this->staticHits = $staticHits;
        parent::__construct($config);
    }


    /**
     * ActiveRecord instance matching the condition, or `null` if nothing matches.
     *
     * @param mixed $condition
     * @return StaticHitsPo|null|mixed
     * @author: lirong
     */
    public function findOne($condition)
    {
        return $this->staticHits::findOne($condition);
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     *
     * @param mixed $condition
     * @return StaticHitsPo|null|mixed
     * @author: lirong
     */
    public function exists($condition): bool
    {
        return $this->staticHits::find()->where($condition)->exists();
    }
}
