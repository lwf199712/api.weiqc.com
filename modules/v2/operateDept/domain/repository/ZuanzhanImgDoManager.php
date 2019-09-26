<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\ZuanzhanImgDo;
use app\modules\v2\operateDept\domain\dto\ZuanzhanImgDto;
use yii\data\ActiveDataProvider;

class ZuanzhanImgDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = ZuanzhanImgDo::class;

    public function listDataProvider(ZuanzhanImgDto $zuanzhanImgDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time',    $zuanzhanImgDto->beginTime])
            ->andFilterWhere(['<', 'upload_time',    $zuanzhanImgDto->endTime])
            ->andFilterWhere(['=', 'name',           $zuanzhanImgDto->name])
            ->andFilterWhere(['=', 'stylist',        $zuanzhanImgDto->stylist])
            ->andFilterWhere(['=', 'audit_status',   $zuanzhanImgDto->audit_status]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $zuanzhanImgDto->getPerPage() ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }


}