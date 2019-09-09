<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\DesignCenterAggregate;
use app\modules\v2\operateDept\domain\dto\DesignCenterDto;
use app\modules\v2\operateDept\domain\dto\DesignCenterForm;
use Exception;
use yii\base\Model;

/**
 * Class DesignCenterController
 * @property-read DesignCenterAggregate $designCenterAggregate
 * @property DesignCenterDto $designCenterDto
 * @property DesignCenterForm $designCenterForm
 * @package app\modules\v2\operateDept\rest
 */
class DesignCenterController extends AdminBaseController
{
    public $dto;

    /** @var DesignCenterAggregate */
    public $designCenterAggregate;
    /** @var DesignCenterDto */
    public $designCenterDto;
    /** @var DesignCenterForm */
    public $designCenterForm;

    public function __construct($id, $module,
                                DesignCenterAggregate $designCenterAggregate,
                                DesignCenterDto $designCenterDto,
                                DesignCenterForm $designCenterForm,
                                $config = [])
    {
        $this->designCenterAggregate = $designCenterAggregate;
        $this->designCenterDto       = $designCenterDto;
        $this->designCenterForm      = $designCenterForm;
        parent::__construct($id, $module, $config);
    }


    public function verbs(): array
    {
        return [
            'index'  => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['POST'],
            'delete' => ['DELETE'],
            'audit'  => ['POST'],
            'read'   => ['GET', 'HEAD'],
            'detail' => ['GET', 'HEAD'],
        ];
    }


    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->designCenterDto->setScenario(DesignCenterDto::SEARCH);
            case 'actionCreate':
                return $this->designCenterForm;
            case 'actionUpdate':
                return $this->designCenterForm;
            case 'actionDelete':
                return $this->designCenterDto;
            case 'actionAudit':
                return $this->designCenterDto->setScenario(DesignCenterDto::AUDIT);
            case 'actionRead':
                return $this->designCenterDto->setScenario(DesignCenterDto::READ);
            case 'actionDetail':
                return $this->designCenterDto;
            default:
                throw new Exception('unKnow actionName ');
        }

    }

    public function actionIndex(): array
    {
        $data = $this->designCenterAggregate->listDesignCenter($this->designCenterDto);
        var_dump($data);die;
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->designCenterAggregate->createDesignCenter($this->designCenterForm);
            return ['新增成功', 200, $result];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->designCenterAggregate->updateDesignCenter($this->designCenterForm);
            return ['修改成功', 200];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    public function actionDelete(): array
    {
        $num = $this->designCenterAggregate->deleteDesignCenter((int)$this->designCenterDto->id);
        return ['删除成功', 200, $num];
    }

    public function actionAudit(): array
    {
        try {
            $this->designCenterAggregate->auditDesignCenter($this->designCenterDto);
            return ['审核成功', 200];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = $this->designCenterAggregate->readDesignCenter((int)$this->designCenterDto->id);
            return ['查看成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

    public function actionDetail(): array
    {
        try {
            $result = $this->designCenterAggregate->detailDesignCenter((int)$this->designCenterDto->id);
            return ['查看成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

}