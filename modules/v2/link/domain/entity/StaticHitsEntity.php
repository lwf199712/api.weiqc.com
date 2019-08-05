<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticHitsDo;

class StaticHitsEntity extends StaticHitsDo
{
    /**
     * 获取独立IP记录
     * @param array $uIdList
     * @return int
     * @author zhuozhen
     */
    public function getStaticHitsData(array $uIdList) : int
    {
        return self::find()->where(['in','u_id',$uIdList])->distinct('u_id')->count('u_id');
    }
}