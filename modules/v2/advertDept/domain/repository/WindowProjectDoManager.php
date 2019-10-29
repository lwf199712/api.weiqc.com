<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/12
 * Time: 13:48
 */

namespace app\modules\v2\advertDept\domain\repository;


use app\common\repository\BaseRepository;
use app\models\dataObject\WindowProjectDo;
use app\modules\v2\advertDept\domain\dto\WindowProjectDto;
use yii\data\ActiveDataProvider;

class WindowProjectDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = WindowProjectDo::class;

    public function listDataProvider(WindowProjectDto $windowProjectDto, array $sort): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>=', 'data_time', $windowProjectDto->beginTime])
            ->andFilterWhere(['<=', 'data_time', $windowProjectDto->endTime])
            ->andFilterWhere(['like', 'account_and_id', $windowProjectDto->account_and_id])
            ->andFilterWhere(['like', 'video_name', $windowProjectDto->video_name])
            ->andFilterWhere(['=', 'delivery_platform', $windowProjectDto->delivery_platform])
            ->andFilterWhere(['=', 'period', $windowProjectDto->period])
            ->andFilterWhere(['like', 'product_name', $windowProjectDto->product_name]);
        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $windowProjectDto->perPage ?? 10,
            ],
            'sort' => [
                'attributes' => array_keys($sort),
                'defaultOrder' => $sort,
            ],
        ]);
    }

    /**
     * 查询导出数据
     * @param WindowProjectDto $windowProjectDto
     * @return array|\yii\db\ActiveRecord[]
     * author: pengguochao
     * Date Time 2019/10/17 18:13
     */
    public function exportData(WindowProjectDto $windowProjectDto): array
    {
        return $this->query
            ->select(['period', 'real_turnover', 'total_turnover', 'consume', 'transaction_data'])
            ->where(['product_name' => $windowProjectDto->product_name])
            ->andWhere(['account_and_id' => $windowProjectDto->account_and_id])
            ->andWhere(['data_time' => $windowProjectDto->data_time])
            ->andWhere(['delivery_platform' => $windowProjectDto->delivery_platform])
            ->orderBy('period ASC')
            ->asArray()
            ->all();
    }
}