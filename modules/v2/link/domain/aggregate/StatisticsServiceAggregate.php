<?php


namespace app\modules\v2\link\domain\aggregate;


use app\modules\v2\link\domain\dto\StatisticsServiceDto;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use app\modules\v2\link\domain\entity\StatisticsServiceEntity;
use mdm\admin\BaseObject;
use yii\base\Exception;

class StatisticsServiceAggregate extends BaseObject
{
    public $staticServiceEntity;

    public function __construct(
        StatisticsServiceEntity $staticServiceEntity,
        $config = [])
    {
        $this->staticServiceEntity = $staticServiceEntity;
        parent::__construct($config);
    }

    /**
     * 得到首页信息
     * @param StatisticsServiceDto $staticServiceDto
     * @return array
     */
    public function getServiceList(StatisticsServiceDto $staticServiceDto):array
    {

        $query = $this->staticServiceEntity->getStaticServiceData($staticServiceDto);

        $provider = $this->staticServiceEntity->getActiveDataProvider($query->asArray(), $staticServiceDto);
        $data = $provider->getModels();
        $count = $provider->getTotalCount();
        return ['list' => $data, 'count' => $count, 'page' => $staticServiceDto->page, 'prePage' => $staticServiceDto->prePage];

    }

    /**
     * 创建服务号
     * @param StatisticsServiceForm $staticServiceForm
     * @return bool
     */
    public function createService(StatisticsServiceForm $staticServiceForm): bool
    {
        return $this->staticServiceEntity->createEntity($staticServiceForm);
    }

    /**
     * 更新公众号
     * @param StatisticsServiceForm $staticServiceForm
     * @return bool
     * @throws Exception
     */
    public function updateService(StatisticsServiceForm $staticServiceForm):bool
    {
        return $this->staticServiceEntity->updateEntity($staticServiceForm);
    }

    /**
     * 软删除公众号
     * @param StatisticsServiceDto $staticServiceDto
     * @return bool
     * @throws Exception
     */
    public function deleteService(StatisticsServiceDto $staticServiceDto):bool
    {
        return $this->staticServiceEntity->deleteEntity($staticServiceDto);
    }


}
