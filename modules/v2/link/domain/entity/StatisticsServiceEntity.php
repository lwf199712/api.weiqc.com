<?php


namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StatisticsServiceDo;
use app\modules\v2\link\domain\dto\StatisticsServiceQuery;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use RuntimeException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class StatisticsServiceEntity extends StatisticsServiceDo
{

    /**
     * @param StatisticsServiceQuery $statisticsServiceQuery
     * @return ActiveQuery
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function getStaticServiceData(StatisticsServiceQuery $statisticsServiceQuery):ActiveQuery
    {
        $query = self::find()
            ->select(['id', 'account', 'name'])
            ->andFilterWhere(
                ['and',
                    ['like', 'account', $statisticsServiceQuery->account],
                    ['like', 'name', $statisticsServiceQuery->name],
                ]
            );
        return $query;
    }


    /**
     * @param ActiveQuery $query
     * @param StatisticsServiceQuery $statisticsServiceQuery
     * @param array $sort
     * @return ActiveDataProvider
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function getActiveDataProvider(ActiveQuery $query, StatisticsServiceQuery $statisticsServiceQuery, array $sort = []): ActiveDataProvider
    {
        $parameter = [
            'query'      => $query,
            'pagination' => ['pageSize' => $statisticsServiceQuery->prePage]
        ];

        if (!empty($sort)) {
            $parameter['sort'] = ['attributes' => array_keys($sort), 'defaultOrder' => $sort];
        }

        return new ActiveDataProvider($parameter);
    }

    /**
     * 创建
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function createEntity(StatisticsServiceForm $statisticsServiceForm): bool
    {

        $account = self::findOne(['account' => $statisticsServiceForm->account]);
        if ($account) {
            throw new RuntimeException('帐号已存在,请重新添加');
        }
        $this->setAttributes($statisticsServiceForm->getAttributes());

        return $this->save();
    }

    /**
     * 更新
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function updateEntity(StatisticsServiceForm $statisticsServiceForm): bool
    {
        $model = self::findOne($statisticsServiceForm->id);
        //帐号存在并
        if ($model !== null){
            if ($model->account === $statisticsServiceForm->account){
                $model->setAttributes($statisticsServiceForm->getAttributes());
                return $model->save();
            }
            if (!self::findOne(['account' => $statisticsServiceForm->account])) {
                $model->setAttributes($statisticsServiceForm->getAttributes());
                return $model->save();
            }
            throw new RuntimeException('帐号已存在，请重新修改');
        }
        throw new RuntimeException('帐号不存在');
    }

    /**
     * 删除
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @author wenxiaomei
     * @date 2019/12/6
     */
   public function deleteEntity(StatisticsServiceForm $statisticsServiceForm): bool
   {
       $model = self::findOne($statisticsServiceForm->id);
       if ($model === null) {
           throw new RuntimeException('找不到这条记录');
       }
       if ($model->deleted_at !== 0) {
           throw new RuntimeException('您已经删除了，请不要重复操作');
       }
       $model->deleted_at = time();
       $model->deleter    = Yii::$app->user->identity->username;
       return $model->save();
   }

}
