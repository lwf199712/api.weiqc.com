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

    /**
     * 更新链接
     * @param int    $id
     * @param string $urlService
     * @return bool
     * @author zhuozhen
     */
    public function updateService(int $id, string $urlService) : bool ;

}
