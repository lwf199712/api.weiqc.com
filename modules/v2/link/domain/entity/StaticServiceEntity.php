<?php


namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StaticServiceDo;
use app\modules\v2\link\domain\dto\StaticServiceDto;
use app\modules\v2\link\domain\dto\StaticServiceForm;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class StaticServiceEntity extends StaticServiceDo
{
    /**
     * 公众号查询信息
     * @param StaticServiceDto $staticServiceDto
     * @return ActiveQuery
     */
    public function getStaticServiceData(StaticServiceDto $staticServiceDto):ActiveQuery
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
     * @param StaticServiceDto $staticServiceDto
     * @param array $sort
     * @return ActiveDataProvider
     */
    public function getActiveDataProvider(ActiveQuery $query, StaticServiceDto $staticServiceDto, array $sort = []): ActiveDataProvider
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
     * @param StaticServiceForm $staticServiceForm
     * @return bool
     */
    public function createEntity(StaticServiceForm $staticServiceForm): bool
    {
        $model = new self();
        $model->setAttributes($staticServiceForm->getAttributes());
        $model->create_time = time();
        return $model->save();
    }

    /**
     * 更新公众号
     * @param StaticServiceForm $staticServiceForm
     * @return bool
     * @throws Exception
     */
    public function updateEntity(StaticServiceForm $staticServiceForm): bool
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
     * @param StaticServiceDto $staticServiceDto
     * @return bool
     * @throws Exception
     */
   public function deleteEntity(StaticServiceDto $staticServiceDto)
   {
       $model = self::findOne($staticServiceDto->id);
       if ($model === null) {
           throw new Exception('找不到这一条记录，不能更新');
       }
       $model->is_delete = 1;
       return $model->save();
   }

}
