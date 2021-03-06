<?php declare(strict_types=1);


namespace app\modules\v2\link\rest;


use app\common\exception\ApiException;
use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\aggregate\StatisticsServiceAggregate;
use app\modules\v2\link\domain\dto\StatisticsServiceQuery;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use yii\base\Model;

/**
 *
 * Class StatisticsServiceController
 * @package app\modules\v2\link\rest
 */
class StatisticsServiceController extends AdminBaseController
{
    /** @var StatisticsServiceAggregate  */
    private $staticServiceAggregate;
    /** @var StatisticsServiceQuery  */
    private $statisticsServiceQuery;
    /** @var StatisticsServiceForm  */
    private $statisticsServiceForm;

    /**
     * StatisticsServiceController constructor.
     * @param $id
     * @param $module
     * @param StatisticsServiceAggregate $staticServiceAggregate
     * @param StatisticsServiceQuery $statisticsServiceQuery
     * @param StatisticsServiceForm $statisticsServiceForm
     * @param array $config
     */
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

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/12
     */
    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'HEAD','OPTIONS'],
            'update' => ['POST', 'HEAD', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    /**
     * @param string $actionName
     * @return Model
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
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
                throw new ApiException('unKnow actionName', 500);
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
        return ['??????????????????', 200, $data];
    }

    /**
     * @return array
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function actionCreate(): array
    {
        $data = $this->staticServiceAggregate->createService($this->statisticsServiceForm);
        return ['????????????', 200, $data];

    }


    /**
     * @return array
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function actionUpdate(): array
    {
        $data = $this->staticServiceAggregate->updateService($this->statisticsServiceForm);
        return ['????????????', 200, $data];
    }

    /**
     * @return array
     * @throws ApiException
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function actionDelete(): array
    {

        $data = $this->staticServiceAggregate->deleteService($this->statisticsServiceForm);
        return ['????????????', 200, $data];
    }

}
