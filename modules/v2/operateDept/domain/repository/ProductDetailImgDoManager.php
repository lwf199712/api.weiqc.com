<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\ProductdetailImgDo;
use app\modules\v2\operateDept\domain\dto\ProductdetailImgDto;
use yii\data\ActiveDataProvider;

class ProductdetailImgDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = ProductdetailImgDo::class;

    public function listDataProvider(ProductdetailImgDto $productdetailImgDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time',    $productdetailImgDto->beginTime])
            ->andFilterWhere(['<', 'upload_time',    $productdetailImgDto->endTime])
            ->andFilterWhere(['=', 'name',           $productdetailImgDto->name])
            ->andFilterWhere(['=', 'stylist',        $productdetailImgDto->stylist])
            ->andFilterWhere(['=', 'audit_status',   $productdetailImgDto->audit_status]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $productdetailImgDto->getPerPage() ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }


}