<?php


namespace app\modules\v2\link\domain\entity;

use app\common\exception\ApiException;
use app\models\dataObject\StatisticsServiceDo;
use app\modules\v2\link\domain\dto\StatisticsServiceQuery;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
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
            ->where(['deleted_at'=> 0])
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
            'pagination' => ['pageSize' => $statisticsServiceQuery->prePage, 'validatePage' => false]
        ];

        if (!empty($sort)) {
            $parameter['sort'] = ['attributes' => array_keys($sort), 'defaultOrder' => $sort];
        }

        return new ActiveDataProvider($parameter);
    }

    /**
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function createEntity(StatisticsServiceForm $statisticsServiceForm): bool
    {

        $account = self::findOne(['account' => $statisticsServiceForm->account,'deleted_at' => 0]);
        if ($account) {
            throw new ApiException('帐号已存在,请重新添加',40002);
        }
        $this->setAttributes($statisticsServiceForm->getAttributes());
        return $this->save();
    }

    /**
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function updateEntity(StatisticsServiceForm $statisticsServiceForm): bool
    {
        $model = self::findOne($statisticsServiceForm->id);
            //帐号存在
        if ($model !== null) {
            if ($model->account === $statisticsServiceForm->account) {
                $model->setAttributes($statisticsServiceForm->getAttributes());
                return $model->save();
            }
            if (!self::findOne(['account' => $statisticsServiceForm->account])) {
                $model->setAttributes($statisticsServiceForm->getAttributes());
                return $model->save();
            }
            throw new ApiException('帐号已存在,请重新修改', 40002);
        }
        throw new ApiException('帐号不存在', 40003);

    }

    /**
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
   public function deleteEntity(StatisticsServiceForm $statisticsServiceForm): bool
   {
       $model = self::findOne($statisticsServiceForm->id);
       if ($model === null) {
           throw new ApiException('找不到这条记录',40003);
       }
       if ($model->deleted_at !== 0) {
           throw new ApiException('您已经删除了，请不要重复操作', 40004);
       }
       $model->deleted_at = time();
       $model->deleter    = Yii::$app->user->identity->username;
       return $model->save();
   }

}
