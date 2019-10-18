<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\IndexImgDo;
use app\modules\v2\operateDept\domain\dto\IndexImgDto;
use yii\data\ActiveDataProvider;

class IndexImgDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = IndexImgDo::class;

    public function listDataProvider(IndexImgDto $indexImgDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time',    $indexImgDto->beginTime])
            ->andFilterWhere(['<', 'upload_time',    $indexImgDto->endTime])
            ->andFilterWhere(['=', 'name',           $indexImgDto->name])
            ->andFilterWhere(['=', 'stylist',        $indexImgDto->stylist])
            ->andFilterWhere(['=', 'audit_status',   $indexImgDto->audit_status])
            ->andFilterWhere(['=', 'size',   $indexImgDto->size]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $indexImgDto->getPerPage() ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }


}