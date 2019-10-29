<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/17
 * Time: 20:39
 */

namespace app\modules\v2\advertDept\domain\repository;


use app\common\repository\BaseRepository;
use app\models\dataObject\ProductLibraryDo;
use app\modules\v2\advertDept\domain\dto\ProductLibraryDto;
use yii\data\ActiveDataProvider;

class ProductLibraryDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = ProductLibraryDo::class;

    public function listDataProvider(ProductLibraryDto $productLibraryDto, array $sort): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>=', 'create_at', $productLibraryDto->beginTime])
            ->andFilterWhere(['<=', 'create_at', $productLibraryDto->endTime])
            ->andFilterWhere(['like', 'product_name', $productLibraryDto->product_name]);
        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $productLibraryDto->perPage ?? 10,
            ],
            'sort' => [
                'attributes' => array_keys($sort),
                'defaultOrder' => $sort,
            ],
        ]);
    }
}