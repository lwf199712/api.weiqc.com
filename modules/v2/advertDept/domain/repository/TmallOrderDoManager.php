<?php declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\TmallOrderDo;
use app\modules\v2\advertDept\domain\dto\TmallOrderDto;
use yii\data\ActiveDataProvider;

class TmallOrderDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = TmallOrderDo::class;

    public function listDataProvider(TmallOrderDto $tmallOrderDto) : array
    {
        $data =$this->query
            ->select(['id','create_at','phone','price'])
            ->where(['>','create_at',$tmallOrderDto->getSince()])
            ->asArray()
            ->all();

        if (empty($data)){
            return [];
        }

        return $data;

        /*return new ActiveDataProvider([
            'query'      => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $params['perPage'] ?? 1000,
            ],
            'sort'       => [
                'attributes'   => ['create_at'],
                'defaultOrder' => ['create_at' => SORT_DESC],
            ],
        ]);*/
    }
}