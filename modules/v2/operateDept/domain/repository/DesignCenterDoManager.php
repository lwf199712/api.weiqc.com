<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\DesignCenterDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterDto;
use yii\data\ActiveDataProvider;

class DesignCenterDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = DesignCenterDo::class;

    public function listDataProvider(DesignCenterDto $designCenterDto): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time', $designCenterDto->beginTime])
            ->andFilterWhere(['<', 'upload_time', $designCenterDto->endTime])
            ->andFilterWhere(['=', 'name', $designCenterDto->name])
            ->andFilterWhere(['=', 'stylist', $designCenterDto->stylist])
            ->andFilterWhere(['=', 'audit_status', $designCenterDto->audit_status]);

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $params['perPage'] ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }


}