<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\entity;


use app\common\utils\IpUtil;
use app\models\dataObject\StaticConversionDo;
use app\modules\v2\link\domain\dto\StaticUrlIntervalAnalyzeDto;
use app\modules\v2\link\domain\dto\StaticUrlVisitDetailDto;

class StaticConversionEntity extends StaticConversionDo
{
    /**
     * 获取特定链接各公众号的转化数
     * @param StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto
     * @return array
     * @author zhuozhen
     */
    public function getCvCountByWeChat(StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto) : array
    {
        self::find()
            ->select(['page','count(id) as conversion_count','wxh'])
            ->where(['u_id' => $staticUrlIntervalAnalyzeDto->id])
            ->andFilterWhere(['between', 'createtime', $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate()])
            ->groupBy('binary(wxh)')
            ->orderBy('conversion_count desc')
            ->asArray()
            ->all();
    }

    /**
     * 将url对应公众号的转化数存到已有的page数组中
     * @param array $conversionList 各公众号的转化数
     * @param int   $cvCount    总转化数
     * @param int   $putVolume  投放量
     * @param array $page       来源url列表
     * @return array
     * @author zhuozhen
     */
    public function fillConversionDataIntoPage(array $conversionList, int $cvCount, int $putVolume , array $page) : array
    {
        return array_map(static function ($hitsRecord) use ($conversionList, $cvCount, $putVolume) {
            $hitsRecord['conversion_count'] = 0;
            foreach ($conversionList as $cvRecord) {
                //在StatisHits的page中查找StatisConversion的wxh
                if (strpos($hitsRecord['page'], $cvRecord['wxh']) !== false) {
                    //转化数
                    $hitsRecord['conversion_count'] += $cvRecord['conversion_count'];
                    //对应公众号
                    $hitsRecord['wxh'] = $cvRecord['wxh'];
                    //分部进粉占比
                    $fansRate               = round($hitsRecord['conversion_count'] / $cvCount, 4);
                    $hitsRecord['fansRate'] = $fansRate * 100 . '%';
                    //投放量
                    $hitsRecord['putVolume'] = round($hitsRecord['conversion_count'] / $cvCount * $putVolume, 2);
                }
            }
            return $hitsRecord;
        }, $page);
    }

    /**
     * 查询统计链接转化数(时段分析)
     * @param StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto
     * @return array
     * @author zhuozhen
     */
    public function queryByStaticUrlForAnalyze(StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto) : array
    {
        return self::find()->select(['u_id', 'ip', 'date', 'page', 'referer', 'createtime'])
            ->where(['u_id' => $staticUrlIntervalAnalyzeDto->id])
            ->andFilterWhere(['between', 'createtime', $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate()])
            ->asArray()
            ->all();
    }


    /**
     * 查询统计链接转化数(投放量)
     * @param int $id
     * @param int $beginTime
     * @param int $endTime
     * @return array
     * @author zhuozhen
     */
    public function queryByStaticUrlForDelivery(int $id ,int $beginTime , int $endTime) : array
    {
        return self::find()->select(['u_id', 'ip', 'date', 'page', 'referer', 'createtime'])
            ->where(['u_id' =>$id] )
            ->andFilterWhere(['between', 'createtime', $beginTime, $endTime])
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


    /**
     * 获取某条链接特定时间的转化数
     * @param int $beginTime
     * @param int $endTime
     * @param int $id
     * @return int
     * @author zhuozhen
     */
    public function getCvCountById(int $beginTime,int $endTime, int $id) : int
    {
        return self::find()
            ->where(['between','createtime',$beginTime,$endTime])
            ->andWhere(['u_id' => $id])
            ->count();
    }

}