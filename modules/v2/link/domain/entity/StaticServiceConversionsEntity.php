<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticServiceConversionsDo;

class StaticServiceConversionsEntity extends StaticServiceConversionsDo
{
    /**
     * 获取转换数
     * @param array $uIdList
     * @return int
     * @author zhuozhen
     */
    public  function getServiceConversionData(array $uIdList) : int
    {
        self::find()->where(['in','u_id',$uIdList])->distinct('u_id')->count('u_id');
    }
}