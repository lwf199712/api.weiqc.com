<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\PhysicalSendStatusDo;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusQuery;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class PhysicalSendStatusDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = PhysicalSendStatusDo::class;

    public function listDataProvider(PhysicalSendStatusQuery $physicalSendStatusQuery): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['=', 'rp_id',   $physicalSendStatusQuery->rp_id]);

        $perPage = $physicalSendStatusQuery->getPerPage();
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
     * 根据id获取一条记录
     * @param $id
     * @return ActiveRecord
     * @author weifeng
     */

    public function findOne($id)
    {
        return $this->model::findOne(['id' => $id]);
    }
}