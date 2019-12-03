<?php


namespace app\modules\v2\link\domain\aggregate;


use app\modules\v2\link\domain\dto\StaticServiceDto;
use app\modules\v2\link\domain\dto\StaticServiceForm;
use app\modules\v2\link\domain\entity\StaticServiceEntity;
use mdm\admin\BaseObject;

class StaticServiceAggregate extends BaseObject
{
    public $staticServiceEntity;

    public function __construct(
        StaticServiceEntity $staticServiceEntity,
        $config = [])
    {
        $this->staticServiceEntity = $staticServiceEntity;
        parent::__construct($config);
    }

    /**
     * 得到首页信息
     * @param StaticServiceDto $staticServiceDto
     * @return array
     */
    public function getServiceList(StaticServiceDto $staticServiceDto):array
    {

        $query = $this->staticServiceEntity->getStaticServiceData($staticServiceDto);

        $provider = $this->staticServiceEntity->getActiveDataProvider($query->asArray(), $staticServiceDto);
        $data = $provider->getModels();
        $count = $provider->getTotalCount();
        return ['list' => $data, 'count' => $count, 'page' => $staticServiceDto->page, 'prePage' => $staticServiceDto->prePage];

    }

    /**
     * 创建服务号
     * @param StaticServiceForm $staticServiceForm
     * @return bool
     */
    public function createService(StaticServiceForm $staticServiceForm): bool
    {
        return $this->staticServiceEntity->createEntity($staticServiceForm);
    }

    /**
     * 更新公众号
     * @param StaticServiceForm $staticServiceForm
     * @return bool
     * @throws \yii\base\Exception
     */
    public function updateService(StaticServiceForm $staticServiceForm):bool
    {
        return $this->staticServiceEntity->updateEntity($staticServiceForm);
    }

    /**
     * 软删除公众号
     * @param StaticServiceDto $staticServiceDto
     * @return bool
     * @throws \yii\base\Exception
     */
    public function deleteService(StaticServiceDto $staticServiceDto):bool
    {
        return $this->staticServiceEntity->deleteEntity($staticServiceDto);
    }


}
