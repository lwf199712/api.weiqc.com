<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\LandingpageImgDo;
use app\modules\v2\operateDept\domain\dto\LandingpageImgDto;
use yii\data\ActiveDataProvider;

class LandingpageImgDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = LandingpageImgDo::class;

    public function listDataProvider(LandingpageImgDto $landingpageImgDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time',    $landingpageImgDto->beginTime])
            ->andFilterWhere(['<', 'upload_time',    $landingpageImgDto->endTime])
            ->andFilterWhere(['=', 'name',           $landingpageImgDto->name])
            ->andFilterWhere(['=', 'stylist',        $landingpageImgDto->stylist])
            ->andFilterWhere(['=', 'audit_status',   $landingpageImgDto->audit_status]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $landingpageImgDto->getPerPage() ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }


}