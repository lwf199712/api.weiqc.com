<?php

namespace app\modules\v1\userAction\service;


use app\models\dataObject\StaticUrlDo;

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
     * @return StaticUrlDo|null|mixed
     * @author: lirong
     */
    public function findOne($condition, $select = null);
}
