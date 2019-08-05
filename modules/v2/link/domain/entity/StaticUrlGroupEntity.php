<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticUrlGroup;

class StaticUrlGroupEntity extends StaticUrlGroup
{
    /**
     * 获取默认一级分组
     * @return array
     * @author zhuozhen
     */
    public  function getDefaultGroup() : array
    {
        self::find()->select(['groupname','id'])->where(['=',0,'parent'])->orderBy('groupname')->all();
    }
}