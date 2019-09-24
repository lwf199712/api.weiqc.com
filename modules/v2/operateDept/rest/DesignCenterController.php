<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\DesignCenterAggregate;
use app\modules\v2\operateDept\domain\dto\DesignCenterDto;
use app\modules\v2\operateDept\domain\dto\DesignCenterForm;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

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
            'index'  => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
            'audit'  => ['POST', 'OPTIONS'],
            'read'   => ['GET', 'HEAD', 'OPTIONS'],
            'detail' => ['GET', 'HEAD', 'OPTIONS'],
        ];
    }


    /**
     *
     * @param string $actionName
     * @return Model
     * @throws Exception
     * @author: weifeng
     */
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
                throw new HttpException('unKnow actionName ');
        }

    }

    public function actionIndex(): array
    {
        $data = $this->designCenterAggregate->listDesignCenter($this->designCenterDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->designCenterAggregate->createDesignCenter($this->designCenterForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->designCenterAggregate->detailDesignCenter((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->designCenterForm->imageFile->baseName.'.'.$this->designCenterForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->designCenterAggregate->updateDesignCenter($this->designCenterForm);
            return ['修改成功', 200,['picture_name'=>$this->designCenterForm->imageFile->baseName.'.'.$this->designCenterForm->imageFile->extension]];
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
            $result = $this->designCenterAggregate->auditDesignCenter($this->designCenterDto);
            $data = [];
            if ($result) {
                $data = $this->designCenterAggregate->detailDesignCenter((int)$this->designCenterDto->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = Yii::$app->request->getHostInfo().$this->designCenterAggregate->readDesignCenter((int)$this->designCenterDto->id);
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