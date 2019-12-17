<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\aggregate;

use app\modules\v2\link\domain\dto\StatisticsServiceQuery;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use app\modules\v2\link\domain\entity\StatisticsServiceEntity;
use mdm\admin\BaseObject;
use app\common\exception\ApiException;

class StatisticsServiceAggregate extends BaseObject
{
    /** @var StatisticsServiceEntity  */
    public $statisticsServiceEntity;

    /**
     * StatisticsServiceAggregate constructor.
     * @param StatisticsServiceEntity $statisticsServiceEntity
     * @param array $config
     */
    public function __construct(
        StatisticsServiceEntity $statisticsServiceEntity,
        $config = [])
    {
        $this->statisticsServiceEntity = $statisticsServiceEntity;
        parent::__construct($config);
    }

    /**
     * @param StatisticsServiceQuery $statisticsServiceQuery
     * @return array
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function getServiceList(StatisticsServiceQuery $statisticsServiceQuery):array
    {
        $query = $this->statisticsServiceEntity->getStaticServiceData($statisticsServiceQuery);
        $provider = $this->statisticsServiceEntity->getActiveDataProvider($query->asArray(), $statisticsServiceQuery);
        $data = $provider->getModels();
        $count = $provider->getTotalCount();
        return ['list' => $data, 'count' => $count, 'page' => $statisticsServiceQuery->page, 'prePage' => $statisticsServiceQuery->prePage];

    }

    /**
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function createService(StatisticsServiceForm $statisticsServiceForm): bool
    {
        return $this->statisticsServiceEntity->createEntity($statisticsServiceForm);
    }

    /**
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function updateService(StatisticsServiceForm $statisticsServiceForm): bool
    {
        return $this->statisticsServiceEntity->updateEntity($statisticsServiceForm);
    }

    /**
     * @param StatisticsServiceForm $statisticsServiceForm
     * @return bool
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function deleteService(StatisticsServiceForm$statisticsServiceForm): bool
    {
        return $this->statisticsServiceEntity->deleteEntity($statisticsServiceForm);
    }


}
