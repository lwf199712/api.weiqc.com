<?php

namespace app\modules\v1\userAction\service;


use app\models\po\StaticUrlPo;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
interface UserActionStaticUrlService
{
    /**
     * @param mixed $condition
     * @param null $select
     * @return StaticUrlPo|null|mixed
     * @author: lirong
     */
    public function findOne($condition, $select = null);
}
