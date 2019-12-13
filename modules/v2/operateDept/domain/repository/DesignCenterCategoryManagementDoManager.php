<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\CategoryManagementDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterCategoryManagementQuery;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Exception;


class DesignCenterCategoryManagementDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = CategoryManagementDo::class;

    public function listDataProvider(DesignCenterCategoryManagementQuery $designCenterCategoryManagementQuery): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['=','type',$designCenterCategoryManagementQuery->type])
            ->andFilterWhere(['like','category',$designCenterCategoryManagementQuery->category]);

        $perPage = $designCenterCategoryManagementQuery->getPerPage();

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
     * 模糊查询
     * Date: 2019/12/7
     * Author: ctl
     * @param DesignCenterCategoryManagementQuery $designCenterCategoryManagementQuery
     * @return array
     * @throws Exception
     */
    public function vagueCheck(DesignCenterCategoryManagementQuery $designCenterCategoryManagementQuery):array
    {
        return $this->model::find()->where(['type' => $designCenterCategoryManagementQuery->type])->andFilterWhere(['like','category',$designCenterCategoryManagementQuery->category])->asArray()->all();
    }
}