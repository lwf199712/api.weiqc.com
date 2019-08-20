<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\repository;


use app\common\repository\BaseRepository;
use app\models\dataObject\TikTokCooperateDo;
use app\modules\v2\marketDept\domain\dto\TikTokCooperateDto;
use yii\data\ActiveDataProvider;

class TikTokCooperateDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = TikTokCooperateDo::class;

    public function listDataProvider(TikTokCooperateDto $tikTokCooperateDto): ActiveDataProvider
    {
        $this->query
            ->andWhere(['=','dept',$tikTokCooperateDto->dept])
            ->andFilterWhere(['>', 'time', $tikTokCooperateDto->beginTime])
            ->andFilterWhere(['<', 'time', $tikTokCooperateDto->endTime])
            ->andFilterWhere(['=', 'nickname', $tikTokCooperateDto->nickname])
            ->andFilterWhere(['=', 'product', $tikTokCooperateDto->product])
            ->andFilterWhere(['=', 'follow', $tikTokCooperateDto->follow])
            ->andFilterWhere(['=', 'final_verify', $tikTokCooperateDto->final_verify])
            ->andFilterWhere(['=', 'draft_verify', $tikTokCooperateDto->draft_verify]);



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