<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticHitsDo;

class StaticHitsEntity extends StaticHitsDo
{
    /**
     * 获取独立IP记录
     * @param array $uIdList
     * @return array
     * @author zhuozhen
     */
    public function getStaticHitsData(array $uIdList) : array
    {
        return self::find()->select(['u_id','count(id) as count'])->where(['in','u_id',$uIdList])->groupBy('u_id')->all();
    }
}