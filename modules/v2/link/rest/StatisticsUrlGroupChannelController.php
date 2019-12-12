<?php declare(strict_types=1);

namespace app\modules\v2\link\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\entity\StatisticsUrlGroupChannelEntity;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelForm;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelQuery;
use app\modules\v2\link\domain\aggregate\StatisticsUrlGroupChannelAggregate;
use app\modules\v2\link\domain\repository\StatisticsUrlGroupChannelDoManager;
use RuntimeException;
use Exception;
use yii\base\Model;

/**
 * Class StatisticsUrlGroupChannelController
 * @package app\modules\v2\link\rest
 */
class StatisticsUrlGroupChannelController extends AdminBaseController
{
    /** @var StatisticsUrlGroupChannelForm */
    public $statisticsUrlGroupChannelForm;
    /** @var StatisticsUrlGroupChannelQuery */
    public $statisticsUrlGroupChannelQuery;
    /** @var StatisticsUrlGroupChannelEntity */
    public $statisticsUrlGroupChannelEntity;
    /** @var StatisticsUrlGroupChannelDoManager */
    public $statisticsUrlGroupChannelDoManager;
    /** @var StatisticsUrlGroupChannelAggregate */
    public $statisticsUrlGroupChannelAggregate;


    public function __construct($id, $module,
                                StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm,
                                StatisticsUrlGroupChannelQuery $statisticsUrlGroupChannelQuery,
                                StatisticsUrlGroupChannelEntity $statisticsUrlGroupChannelEntity,
                                StatisticsUrlGroupChannelAggregate $statisticsUrlGroupChannelAggregate,
                                StatisticsUrlGroupChannelDoManager $statisticsUrlGroupChannelDoManager
        , $config = [])
    {
        $this->statisticsUrlGroupChannelForm = $statisticsUrlGroupChannelForm;
        $this->statisticsUrlGroupChannelQuery = $statisticsUrlGroupChannelQuery;
        $this->statisticsUrlGroupChannelAggregate = $statisticsUrlGroupChannelAggregate;
        $this->statisticsUrlGroupChannelEntity = $statisticsUrlGroupChannelEntity;
        $this->statisticsUrlGroupChannelDoManager = $statisticsUrlGroupChannelDoManager;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @author: qzr
     * Date: 2019/12/5
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->statisticsUrlGroupChannelQuery;
            case 'actionCreate':
                return $this->statisticsUrlGroupChannelForm->setScenario(statisticsUrlGroupChannelForm::CREATE);
            case 'actionUpdate':
                return $this->statisticsUrlGroupChannelForm->setScenario(statisticsUrlGroupChannelForm::UPDATE);
            case 'actionDelete':
                return $this->statisticsUrlGroupChannelForm->setScenario(statisticsUrlGroupChannelForm::DELETE);
            default:
                throw new RuntimeException('UnKnow ActionName', 500);
        }
    }

    /**
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function actionIndex(): array
    {
        $data = $this->statisticsUrlGroupChannelAggregate->listChannelData($this->statisticsUrlGroupChannelQuery);
        return ['success', 200, $data];
    }

    /**
     * 创建渠道
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function actionCreate(): array
    {
        try {
            $data = $this->statisticsUrlGroupChannelAggregate->createChannel($this->statisticsUrlGroupChannelForm);
            return ['success', 200, $data];
        } catch (Exception $exception) {
            return ['fail', 500, $exception->getMessage()];
        }
    }

    /**修改渠道
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function actionUpdate(): array
    {
        try {
            $data = $this->statisticsUrlGroupChannelAggregate->updateChannel($this->statisticsUrlGroupChannelForm);
            return ['success', 200, $data];
        } catch (Exception $exception) {
            return ['fail', 500, $exception->getMessage()];
        }
    }

    /**
     * 删除渠道
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function actionDelete(): array
    {
        try {
            $data = $this->statisticsUrlGroupChannelAggregate->deleteChannel($this->statisticsUrlGroupChannelForm);
            return ['success', 200, $data];
        } catch (Exception $exception) {
            return ['fail', 500, $exception->getMessage()];
        }
    }
}
