<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\DesignCenterProviderInfoDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoQuery;
use yii\data\ActiveDataProvider;

class DesignCenterProviderInfoDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = DesignCenterProviderInfoDo::class;

    /**
     * 查询列表数据
     * @param DesignCenterProviderInfoQuery $designCenterProviderInfoQuery
     * @return ActiveDataProvider
     * @author weifeng
     */
    public function listDataProvider(DesignCenterProviderInfoQuery $designCenterProviderInfoQuery): ActiveDataProvider
    {
        $fields = ['id', 'name', 'quoted_price', 'site', 'recommended_reason', 'contact_way', 'remark', 'reference_case', 'flag'];
        $this->query->select($fields)
                    ->andFilterWhere(['like', 'name', $designCenterProviderInfoQuery->name])
                    ->andFilterWhere(['=', 'flag', $designCenterProviderInfoQuery->flag]);

        return new ActiveDataProvider([
            'query'        => $this->query->asArray(),
            'pagination'   => [
                'pageSize' => $designCenterProviderInfoQuery->getPerPage() ?? 10,
            ],
            'sort' => [
                'attributes'   => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
    }
}