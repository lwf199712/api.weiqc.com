<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\DesignCenterImageDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageQuery;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class DesignCenterImageDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = DesignCenterImageDo::class;

    public function listDataProvider(DesignCenterImageQuery $designCenterImageQuery): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'upload_time',    $designCenterImageQuery->beginTime])
            ->andFilterWhere(['<', 'upload_time',    $designCenterImageQuery->endTime])
            ->andFilterWhere(['like', 'name',        $designCenterImageQuery->name])
            ->andFilterWhere(['=', 'stylist',        $designCenterImageQuery->stylist])
            ->andFilterWhere(['=', 'audit_status',   $designCenterImageQuery->audit_status])
            ->andFilterWhere(['=', 'size',           $designCenterImageQuery->size])
            ->andFilterWhere(['=', 'type',           $designCenterImageQuery->type]);

        if (isset($designCenterImageQuery->category) && $designCenterImageQuery->category !== '') {
            $ids = array_filter(explode(',', $designCenterImageQuery->category));
            foreach ($ids as $key => $value) {
                $this->query->andWhere(['or', ['LIKE', 'category', "%,$value", false], ['LIKE', 'category', "$value,%", false], ['LIKE', 'category', ",$value,"], ['=', 'category', $value]]);
            }
        }

        $perPage = $designCenterImageQuery->getPerPage();
        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $perPage ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
    }

    /**
     * 查看详情
     * @param int $id
     * @return ActiveRecord
     * @author zhuozhen
     */
    public function viewData(int $id) : ActiveRecord
    {
        return $this->model::findOne(['id' => $id]);
    }


}
