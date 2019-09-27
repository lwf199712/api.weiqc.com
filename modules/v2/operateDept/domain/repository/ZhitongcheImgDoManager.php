<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\ZhitongcheImgDo;
use app\modules\v2\operateDept\domain\dto\ZhitongcheImgDto;
use yii\data\ActiveDataProvider;

class ZhitongcheImgDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = ZhitongcheImgDo::class;

    public function listDataProvider(ZhitongcheImgDto $zhitongcheImgDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time',    $zhitongcheImgDto->beginTime])
            ->andFilterWhere(['<', 'upload_time',    $zhitongcheImgDto->endTime])
            ->andFilterWhere(['=', 'name',           $zhitongcheImgDto->name])
            ->andFilterWhere(['=', 'stylist',        $zhitongcheImgDto->stylist])
            ->andFilterWhere(['=', 'audit_status',   $zhitongcheImgDto->audit_status]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $zhitongcheImgDto->getPerPage() ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }


}