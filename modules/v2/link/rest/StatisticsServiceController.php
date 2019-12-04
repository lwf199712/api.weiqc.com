<?php


namespace app\modules\v2\link\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\aggregate\StatisticsServiceAggregate;
use app\modules\v2\link\domain\dto\StatisticsServiceDto;
use app\modules\v2\link\domain\dto\StatisticsServiceForm;
use RuntimeException;
use yii\base\Exception;
use yii\base\Model;

class StatisticsServiceController extends AdminBaseController
{
    private $staticServiceAggregate;
    private $staticServiceDto;
    private $staticServiceForm;

    public function __construct($id, $module,
                                StatisticsServiceAggregate $staticServiceAggregate,
                                StatisticsServiceDto $staticServiceDto,
                                StatisticsServiceForm $staticServiceForm,
                                $config = [])
    {
        $this->staticServiceAggregate = $staticServiceAggregate;
        $this->staticServiceDto       = $staticServiceDto;
        $this->staticServiceForm      = $staticServiceForm;
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
                return $this->staticServiceForm->setScenario(StatisticsServiceForm::UPDATE);
            case 'actionCreate':
                return $this->staticServiceForm;
            case 'actionIndex':
                return $this->staticServiceDto->setScenario($this->staticServiceDto::READ);
            case 'actionDelete':
                return $this->staticServiceDto->setScenario($this->staticServiceDto::DELETE);
            default:
                throw new RuntimeException('unKnow actionName', 500);
        }
    }

    /**
     * 首页信息
     * @return array
     */
    public function actionIndex(): array
    {

        $data = $this->staticServiceAggregate->getServiceList($this->staticServiceDto);
        return ['返回数据成功', 200, $data];
    }

    /**
     * 创建公众号
     * @return array
     */
    public function actionCreate(): array
    {
        $data = $this->staticServiceAggregate->createService($this->staticServiceForm);
        return ['插入成功', 200, $data];

    }

    /**
     * 更新
     * @return array
     * @throws Exception
     */
    public function actionUpdate(): array
    {
        $data = $this->staticServiceAggregate->updateService($this->staticServiceForm);
        return ['更新成功', 200, $data];
    }

    /**
     * 软删除
     * @return array
     * @throws Exception
     */
    public function actionDelete(): array
    {
        $data = $this->staticServiceAggregate->deleteService($this->staticServiceDto);
        return ['删除成功', 200, $data];
    }

}
