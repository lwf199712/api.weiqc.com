<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\DesignCenterVideoDo;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use app\modules\v2\operateDept\domain\dto\DesignCenterHomeVideoQuery;

class DesignCenterHomeVideoDoManager extends BaseRepository
{
    /** model */
    public static $modelClass = DesignCenterVideoDo::class;

    /**
     * Home Video DataProvider
     * Date: 2019/10/31
     * Author: ctl
     * @param DesignCenterHomeVideoQuery $designCenterHomeVideoQuery
     * @return ActiveDataProvider
     */
    public function listDataProvider(DesignCenterHomeVideoQuery $designCenterHomeVideoQuery) : ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'design_finish_time',    $designCenterHomeVideoQuery->beginTime])
            ->andFilterWhere(['<', 'design_finish_time',    $designCenterHomeVideoQuery->endTime])
            ->andFilterWhere(['like', 'name',        $designCenterHomeVideoQuery->name])
            ->andFilterWhere(['=', 'audit_status',   $designCenterHomeVideoQuery->audit_status]);

        if (isset($designCenterHomeVideoQuery->category) && $designCenterHomeVideoQuery->category !== '') {
            $ids = array_filter(explode(',', $designCenterHomeVideoQuery->category));
            foreach ($ids as $key => $value) {
                $this->query->andWhere(['or', ['LIKE', 'category', "%,$value", false], ['LIKE', 'category', "$value,%", false], ['LIKE', 'category', ",$value,"], ['=', 'category', $value]]);
            }
        }

        $perPage = $designCenterHomeVideoQuery->getPerPage();
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
     * 查看指定视频详情
     * Date: 2019/10/31
     * Author: ctl
     * @param int $id
     * @return ActiveRecord
     */
    public function detailData(int $id) :ActiveRecord
    {
        return $this->model::findOne(['id'=>$id]);
    }
}
