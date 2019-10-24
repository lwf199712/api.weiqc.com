<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/12
 * Time: 13:44
 */

namespace app\modules\v2\advertDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\advertDept\domain\aggregate\WindowProjectAggregate;
use app\modules\v2\advertDept\domain\dto\WindowProjectDto;
use app\modules\v2\advertDept\domain\dto\WindowProjectForm;
use Exception;
use yii\base\Model;

class WindowProjectController extends AdminBaseController
{
    public $dto;

    /** @var WindowProjectAggregate */
    public $windowProjectAggregate;
    /** @var WindowProjectDto */
    public $windowProjectDto;
    /** @var WindowProjectForm */
    public $windowProjectForm;

    public function __construct($id, $module,
                                WindowProjectAggregate $windowProjectAggregate,
                                WindowProjectForm $windowProjectForm,
                                WindowProjectDto $windowProjectDto,
                                $config = [])
    {
        $this->windowProjectAggregate = $windowProjectAggregate;
        $this->windowProjectForm = $windowProjectForm;
        $this->windowProjectDto = $windowProjectDto;
        parent::__construct($id, $module, $config);
    }

    public function verbs():array
    {
        return [
            'index'  => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'read'   => ['GET', 'HEAD', 'OPTIONS'],
            'update' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
            'export'  => ['GET', 'OPTIONS'],
        ];
    }

    /**
     * @param string $actionName
     * @return Model
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/16 10:33
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName){
            case 'actionIndex':
                return $this->windowProjectDto->setScenario(WindowProjectDto::SEARCH);
            case 'actionCreate':
                return $this->windowProjectForm->setScenario(WindowProjectForm::CREATE);
            case 'actionRead':
                return $this->windowProjectDto->setScenario(WindowProjectDto::READ);
            case 'actionUpdate':
                return $this->windowProjectForm->setScenario(WindowProjectForm::UPDATE);
            case 'actionDelete':
                return $this->windowProjectDto->setScenario(WindowProjectDto::DELETE);
            case 'actionExport':
                return $this->windowProjectDto->setScenario(WindowProjectDto::EXPORT);
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    /**
     * 查询橱窗项目数据
     * @return array
     * author: pengguochao
     * Date Time 2019/10/16 18:19
     */
    public function actionIndex(): array
    {
        $data = $this->windowProjectAggregate->listWindowProject($this->windowProjectDto);
        return ['成功返回数据', 200, $data];
    }

    /**
     * 添加橱窗项目数据
     * @return array
     * author: pengguochao
     * Date Time 2019/10/16 11:02
     */
    public function actionCreate(): array
    {
        try {
            $this->windowProjectForm->consumeData();
            $this->windowProjectAggregate->createWindowProject($this->windowProjectForm);
            return ['添加橱窗项目成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 获取橱窗项目详情
     * @return array
     * author: pengguochao
     * Date Time 2019/10/17 9:10
     */
    public function actionRead(): array
    {
        try {
            $data = $this->windowProjectAggregate->detailWindowProject((int)$this->windowProjectDto->id);
            return ['查看详情成功', 200, $data];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 更新橱窗项目
     * @return array
     * author: pengguochao
     * Date Time 2019/10/17 12:25
     */
    public function actionUpdate(): array
    {
        try {
            $this->windowProjectAggregate->updateWindowProject($this->windowProjectForm);
            return ['更新橱窗项目成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 删除橱窗项目
     * @return array
     * author: pengguochao
     * Date Time 2019/10/17 13:37
     */
    public function actionDelete(): array
    {
        try {
            $this->windowProjectAggregate->deleteWindowProject($this->windowProjectDto);
            return ['删除橱窗项目成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 查询导出数据
     * author: pengguochao
     * Date Time 2019/10/17 18:16
     */
    public function actionExport(): ?array
    {
        try {
            $this->windowProjectAggregate->exportWindowProject($this->windowProjectDto);
            return ['导出橱窗项目成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }
}