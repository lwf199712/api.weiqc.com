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
            'count(CASE WHEN audit_status=0 THEN 1 END) as stayAudit',
            'count(CASE WHEN audit_status=1 THEN 1 END) as pass',
            'count(CASE WHEN audit_status=2 THEN 1 END) as notPass'
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


}