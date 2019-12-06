<?php


namespace app\modules\v2\link\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\aggregate\StatisticsServiceAggregate;
use app\modules\v2\link\domain\dto\StatisticsServiceQuery;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use RuntimeException;
use yii\base\Model;

class StatisticsServiceController extends AdminBaseController
{
    private $staticServiceAggregate;
    private $statisticsServiceQuery;
    private $statisticsServiceForm;

    public function __construct($id, $module,
                                StatisticsServiceAggregate $staticServiceAggregate,
                                StatisticsServiceQuery $statisticsServiceQuery,
                                StatisticsServiceForm $statisticsServiceForm,
                                $config = [])
    {
        $this->staticServiceAggregate = $staticServiceAggregate;
        $this->statisticsServiceQuery       = $statisticsServiceQuery;
        $this->statisticsServiceForm      = $statisticsServiceForm;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'HEAD','OPTIONS'],
            'update' => ['POST', 'HEAD', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionUpdate':
                return $this->statisticsServiceForm->setScenario($this->statisticsServiceForm::UPDATE);
            case 'actionCreate':
                return $this->statisticsServiceForm->setScenario($this->statisticsServiceForm::CREATE);
            case 'actionIndex':
                return $this->statisticsServiceQuery->setScenario($this->statisticsServiceQuery::READ);
            case 'actionDelete':
                return $this->statisticsServiceForm->setScenario($this->statisticsServiceForm::DELETE);
            default:
                throw new RuntimeException('unKnow actionName', 500);
        }
    }

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function actionIndex(): array
    {

        $data = $this->staticServiceAggregate->getServiceList($this->statisticsServiceQuery);
        return ['返回数据成功', 200, $data];
    }

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function actionCreate(): array
    {
        $data = $this->staticServiceAggregate->createService($this->statisticsServiceForm);
        return ['插入成功', 200, $data];

    }

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function actionUpdate(): array
    {
        $data = $this->staticServiceAggregate->updateService($this->statisticsServiceForm);
        return ['更新成功', 200, $data];
    }

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/6
     */
    public function actionDelete(): array
    {

        $data = $this->staticServiceAggregate->deleteService($this->statisticsServiceForm);
        return ['删除成功', 200, $data];
    }

}
