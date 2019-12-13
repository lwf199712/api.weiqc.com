<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\aggregate;

use app\modules\v2\link\domain\repository\StatisticsUrlGroupChannelDoManager;
use yii\base\BaseObject;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelQuery;
use yii\db\Exception;
use app\modules\v2\link\domain\entity\StatisticsUrlGroupChannelEntity;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelForm;

/**
 * Class StatisticsUrlGroupChannelAggregate
 * @property StatisticsUrlGroupChannelEntity $statisticsUrlGroupChannelEntity
 * @property StatisticsUrlGroupChannelDoManager $statisticsUrlGroupChannelDoManager
 * @property StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
 * @property StatisticsUrlGroupChannelQuery $statisticsUrlGroupChannelQuery
 * @package app\modules\v2\link\domain\aggregate
 */
class StatisticsUrlGroupChannelAggregate extends BaseObject
{
    /** @var StatisticsUrlGroupChannelEntity */
    private $statisticsUrlGroupChannelEntity;
    /** @var StatisticsUrlGroupChannelDoManager */
    private $statisticsUrlGroupChannelDoManager;
    /** @var StatisticsUrlGroupChannelForm */
    private $statisticsUrlGroupChannelForm;
    /** @var StatisticsUrlGroupChannelQuery */
    private $statisticsUrlGroupChannelQuery;

    public function __construct(
        StatisticsUrlGroupChannelEntity $statisticsUrlGroupChannelEntity,
        StatisticsUrlGroupChannelDoManager $statisticsUrlGroupChannelDoManager,
        StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm,
        StatisticsUrlGroupChannelQuery $statisticsUrlGroupChannelQuery,
        $config = [])
    {
        $this->statisticsUrlGroupChannelEntity = $statisticsUrlGroupChannelEntity;
        $this->statisticsUrlGroupChannelForm = $statisticsUrlGroupChannelForm;
        $this->statisticsUrlGroupChannelQuery = $statisticsUrlGroupChannelQuery;
        $this->statisticsUrlGroupChannelDoManager = $statisticsUrlGroupChannelDoManager;
        parent::__construct($config);
    }

    /**
     * @param StatisticsUrlGroupChannelQuery $statisticsUrlGroupChannelQuery
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function listChannelData(StatisticsUrlGroupChannelQuery  $statisticsUrlGroupChannelQuery): array
    {
        $list = $this->statisticsUrlGroupChannelDoManager->listDataProvider($statisticsUrlGroupChannelQuery)->getModels();
        $data['list'] = $list;
        $data['totalCount'] = $this->statisticsUrlGroupChannelDoManager->listDataProvider($statisticsUrlGroupChannelQuery)->getTotalCount();
        return $data;
    }

    /**
     * 创建渠道
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @throws Exception
     * @author: qzr
     * Date: 2019/12/5
     */
    public function createChannel(StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm): bool
    {
        $result = $this->statisticsUrlGroupChannelEntity->createEntity($statisticsUrlGroupChannelForm);
        if (!$result) {
            throw new Exception('添加失败');
        }
        return $result;
    }

    /**
     * 删除渠道
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @throws Exception
     * @author: qzr
     */
    public function deleteChannel(StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm): bool
    {
        $result = $this->statisticsUrlGroupChannelEntity->deleteEntity($statisticsUrlGroupChannelForm);
        if (!$result) {
            throw new Exception('删除失败');
        }
        return $result;
    }

    /**
     * 修改渠道
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @throws Exception
     * @author: qzr
     */
    public function updateChannel(StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm): bool
    {
        $result = $this->statisticsUrlGroupChannelEntity->updateEntity($statisticsUrlGroupChannelForm);
        if (!$result) {
            throw new Exception('修改失败');
        }
        return $result;
    }
}

