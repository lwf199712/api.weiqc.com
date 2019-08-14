<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\repository;


use app\common\repository\BaseRepository;
use app\models\dataObject\TikTokResourceBaseCooperateDo;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseCooperateDto;
use yii\data\ActiveDataProvider;

class TikTokResourceBaseCooperateDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = TikTokResourceBaseCooperateDo::class;

    public function listDataProvider(TikTokResourceBaseCooperateDto $tikTokResourceBaseCooperateDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['=', 'resource_base_id', $tikTokResourceBaseCooperateDto->resource_base_id])
            ->andFilterWhere(['=', 'kol_name', $tikTokResourceBaseCooperateDto->kol_name])
            ->andFilterWhere(['=', 'account_id', $tikTokResourceBaseCooperateDto->account_id])
            ->andFilterWhere(['=', 'account_type', $tikTokResourceBaseCooperateDto->account_type])
            ->andFilterWhere(['=', 'cooperate_info', $tikTokResourceBaseCooperateDto->cooperate_info]);



        return new ActiveDataProvider([
            'query'      => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $params['perPage'] ?? 10,
            ],
            'sort'       => [
                'attributes'   => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }
}