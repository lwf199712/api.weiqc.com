<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticHitsDo;
use app\modules\v2\link\domain\dto\StaticUrlReportDto;

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

    /**
     *
     * @param StaticUrlReportDto $staticUrlReportDto
     * @return array
     * @author zhuozhen
     */
    public function queryByStaticUrl(StaticUrlReportDto $staticUrlReportDto) : array
    {
        return self::find()->select(['u_id','ip','date','createtime'])
            ->where(['u_id' => $staticUrlReportDto->id])
            ->andFilterWhere(['between','createtime',$staticUrlReportDto->getBeginDate(),$staticUrlReportDto->getEndDate()])
            ->all();
    }
}