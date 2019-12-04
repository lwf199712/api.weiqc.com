<?php


namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StaticServiceDo;
use app\modules\v2\link\domain\dto\StatisticsServiceDto;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class StatisticsServiceEntity extends StaticServiceDo
{
    /**
     * 公众号查询信息
     * @param StatisticsServiceDto $staticServiceDto
     * @return ActiveQuery
     */
    public function getStaticServiceData(StatisticsServiceDto $staticServiceDto):ActiveQuery
    {
        $query = self::find()
            ->select(['id', 'account', 'name'])
            ->andFilterWhere(
                ['and',
                    ['like', 'account', $staticServiceDto->account],
                    ['like', 'name', $staticServiceDto->name],
                ]
            );



        return $query;
    }


    /**
     * 数据提供器
     * @param ActiveQuery $query
     * @param StatisticsServiceDto $staticServiceDto
     * @param array $sort
     * @return ActiveDataProvider
     */
    public function getActiveDataProvider(ActiveQuery $query, StatisticsServiceDto $staticServiceDto, array $sort = []): ActiveDataProvider
    {
        $parameter = [
            'query'      => $query,
            'pagination' => ['pageSize' => $staticServiceDto->prePage]
        ];

        if (!empty($sort)) {
            $parameter['sort'] = ['attributes' => array_keys($sort), 'defaultOrder' => $sort];
        }

        return new ActiveDataProvider($parameter);
    }

    /**
     * 新建公众号
     * @param StatisticsServiceForm $staticServiceForm
     * @return bool
     */
    public function createEntity(StatisticsServiceForm $staticServiceForm): bool
    {
        $model = new self();
        $model->setAttributes($staticServiceForm->getAttributes());
        $model->create_time = time();
        return $model->save();
    }

    /**
     * 更新公众号
     * @param StatisticsServiceForm $staticServiceForm
     * @return bool
     * @throws Exception
     */
    public function updateEntity(StatisticsServiceForm $staticServiceForm): bool
    {
        $model = self::findOne($staticServiceForm->id);
        if ($model === null) {
            throw new Exception('找不到这一条记录，不能更新');
        }

        $model->setAttributes($staticServiceForm->getAttributes());
        $model->create_time = time();
        return $model->save();
    }

    /**
     * 软删除公众号
     * @param StatisticsServiceDto $staticServiceDto
     * @return bool
     * @throws Exception
     */
   public function deleteEntity(StatisticsServiceDto $staticServiceDto): bool
   {
       $model = self::findOne($staticServiceDto->id);
       if ($model === null) {
           throw new Exception('找不到这一条记录，不能删除');
       }
       $model->is_delete = 1;
       return $model->save();
   }

}
