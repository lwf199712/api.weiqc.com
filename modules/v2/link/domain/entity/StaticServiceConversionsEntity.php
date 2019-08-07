<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticServiceConversionsDo;

class StaticServiceConversionsEntity extends StaticServiceConversionsDo
{
    /**
     * 获取转换数
     * @param array $uIdList
     * @return array
     * @author zhuozhen
     */
    public  function getServiceConversionData(array $uIdList) : array
    {
        return self::find()->select(['u_id','count(id) as count'])->where(['in','u_id',$uIdList])->groupBy('u_id')->all();
    }
}