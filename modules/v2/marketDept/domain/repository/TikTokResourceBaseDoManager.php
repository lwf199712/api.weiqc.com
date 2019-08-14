<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\repository;


use app\common\repository\BaseRepository;
use app\models\dataObject\TikTokResourceBaseDo;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseDto;
use yii\data\ActiveDataProvider;

class TikTokResourceBaseDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = TikTokResourceBaseDo::class;

    public function viewData(int $tikTokResourceBaseId) : array
    {
        return $this->query->where(['=','id',$tikTokResourceBaseId])->asArray()->one();
    }

    public function listDataProvider(TikTokResourceBaseDto $tikTokResourceBaseDto) : ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['=','mcn_company_name',$tikTokResourceBaseDto->mcn_company_name])
            ->andFilterWhere(['=','identity',$tikTokResourceBaseDto->identity])
            ->andFilterWhere(['=','follow',$tikTokResourceBaseDto->follow])
            ->andFilterWhere(['>','update_at',$tikTokResourceBaseDto->update_at_start])
            ->andFilterWhere(['<','update_at',$tikTokResourceBaseDto->update_at_end]);


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