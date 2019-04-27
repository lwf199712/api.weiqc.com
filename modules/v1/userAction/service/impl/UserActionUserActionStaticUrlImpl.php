<?php

namespace app\modules\v1\userAction\service\impl;

use app\models\dataObject\StaticUrlDo;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticUrlDo $staticUrl
 * @author: lirong
 */
class UserActionUserActionStaticUrlImpl extends BaseObject implements UserActionStaticUrlService
{
    /* @var StaticUrlDo */
    private $staticUrl;

    public function __construct(StaticUrlDo $staticUrl, $config = [])
    {
        $this->staticUrl = $staticUrl;
        parent::__construct($config);
    }

    /**
     * find one
     *
     * @param mixed $condition
     * @param null $select
     * @return StaticUrlDo|mixed|null
     * @author: lirong
     */
    public function findOne($condition, $select = null)
    {
        if ($select === null) {
            return $this->staticUrl::findOne($condition);
        }
        return $this->staticUrl::find()->select($select)->where($condition)->one();
    }
}
