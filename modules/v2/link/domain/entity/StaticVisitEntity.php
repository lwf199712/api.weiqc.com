<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\common\utils\IpUtil;
use app\models\dataObject\StaticVisitDo;
use app\modules\v2\link\domain\dto\StaticUrlIntervalAnalyzeDto;
use app\modules\v2\link\domain\dto\StaticUrlReportDto;
use app\modules\v2\link\domain\dto\StaticUrlVisitDetailDto;
use yii\db\ActiveRecord;

class StaticVisitEntity extends StaticVisitDo
{
    /**
     * 查询统计链接重复点击
     * @param StaticUrlReportDto $staticUrlReportDto
     * @return array
     * @author zhuozhen
     */
    public function queryByStaticUrlForReport(StaticUrlReportDto $staticUrlReportDto)
    {
        return self::find()->select(['u_id','ip','date','createtime'])
            ->where(['u_id' => $staticUrlReportDto->id])
            ->andFilterWhere(['between','createtime',$staticUrlReportDto->getBeginDate(),$staticUrlReportDto->getEndDate()])
            ->asArray()
            ->all();
    }

    /**
     * 查询统计链接重复点击
     * @param StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto
     * @return array
     * @author zhuozhen
     */
    public function queryByStaticUrlForAnalyze(StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto): array
    {
        return self::find()->select(['u_id', 'ip', 'date', 'page', 'referer', 'createtime'])
            ->where(['u_id' => $staticUrlIntervalAnalyzeDto->id])
            ->andFilterWhere(['between', 'createtime', $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate()])
            ->asArray()
            ->all();
    }

    /**
     * 查询访问明细
     * @param StaticUrlVisitDetailDto $staticUrlVisitDetailDto
     * @return array
     * @author zhuozhen
     */
    public function queryVisitDetail(StaticUrlVisitDetailDto $staticUrlVisitDetailDto) : array
    {
        return self::find()
            //TODO 根据field查找
            ->andFilterWhere(['like', 'referer', $staticUrlVisitDetailDto->referer])//来源
            ->andFilterWhere(['like', 'page', $staticUrlVisitDetailDto->page])//停留
            ->andFilterWhere(['=', 'ip', IpUtil::ip2int($staticUrlVisitDetailDto->ip)])//ip
            ->andFilterWhere(['like', 'country', $staticUrlVisitDetailDto->country])//位置
            ->andFilterWhere(['like', 'area', $staticUrlVisitDetailDto->area])//接入商
            ->asArray()
            ->all();
    }

}