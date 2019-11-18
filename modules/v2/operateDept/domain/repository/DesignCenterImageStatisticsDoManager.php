<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\DesignCenterImageDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageStatisticsDto;
use yii\data\ActiveDataProvider;

class DesignCenterImageStatisticsDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = DesignCenterImageDo::class;

    public function listDataProvider(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): ActiveDataProvider
    {
        $field = ['id', 'stylist',
            'count(CASE WHEN type="homePage" THEN 1 END) as homePage',
            'count(CASE WHEN type="mainImage" THEN 1 END) as mainImage',
            'count(CASE WHEN type="productDetail" THEN 1 END) as productDetail',
            'count(CASE WHEN type="drillShow" THEN 1 END) as drillShow',
            'count(CASE WHEN type="throughCar" THEN 1 END) as throughCar',
            'count(CASE WHEN type="landingPage" THEN 1 END) as landingPage',
            'count(CASE WHEN type="tweet" THEN 1 END) as tweet',
            'count(CASE WHEN type="describe790" THEN 1 END) as describe790',
            'count(CASE WHEN type="storeActivity" THEN 1 END) as storeActivity',
            'count(CASE WHEN type="slideShow" THEN 1 END) as slideShow',
            'count(CASE WHEN type="videoMainImage" THEN 1 END) as videoMainImage',
            'count(CASE WHEN type="truingScene" THEN 1 END) as truingScene',
        ];

        $this->query->select($field)
            ->andFilterWhere(['>', 'design_finish_time', $designCenterImageStatisticsDto->beginTime])
            ->andFilterWhere(['<', 'design_finish_time', $designCenterImageStatisticsDto->endTime])
            ->andFilterWhere(['=', 'stylist',            $designCenterImageStatisticsDto->stylist])
            ->groupBy('stylist');

        $perPage = $designCenterImageStatisticsDto->getPerPage();
        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $perPage ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
    }

    /**
     * 查询审核统计
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return ActiveDataProvider
     * @author: weifeng
     */
    public function auditStatistics(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): ActiveDataProvider
    {
        $field = ['id', 'stylist',
            'count(CASE WHEN audit_status=0 THEN 1 END) as stayAudit',
            'count(CASE WHEN audit_status=1 THEN 1 END) as pass',
            'count(CASE WHEN audit_status=2 THEN 1 END) as notPass'
        ];

        $this->query->select($field)
            ->andFilterWhere(['>', 'design_finish_time', $designCenterImageStatisticsDto->beginTime])
            ->andFilterWhere(['<', 'design_finish_time', $designCenterImageStatisticsDto->endTime])
            ->andFilterWhere(['=', 'stylist',            $designCenterImageStatisticsDto->stylist])
            ->andFilterWhere(['=', 'type',               $designCenterImageStatisticsDto->type])
            ->groupBy('stylist');

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
        ]);

    }

    /**
     * 查询个人图片审核情况
     * Date: 2019/11/18
     * Author: ctl
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return ActiveDataProvider
     */
    public function personalStatistics(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): ActiveDataProvider
    {
        $field = ['id', 'stylist',
            'count(CASE WHEN audit_status=0 THEN 1 END) as stayAudit',
            'count(CASE WHEN audit_status=1 THEN 1 END) as pass',
            'count(CASE WHEN audit_status=2 THEN 1 END) as notPass'
        ];

        $this->query->select($field)
            ->andFilterWhere(['=', 'stylist', $designCenterImageStatisticsDto->stylist]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
        ]);
    }

}