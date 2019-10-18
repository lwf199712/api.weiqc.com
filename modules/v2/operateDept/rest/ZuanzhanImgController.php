<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\ZuanzhanImgAggregate;
use app\modules\v2\operateDept\domain\dto\ZuanzhanImgDto;
use app\modules\v2\operateDept\domain\dto\ZuanzhanImgForm;
use Exception;
use Yii;
use yii\base\Model;

class ZuanzhanImgController extends AdminBaseController
{
    public $dto;

    /** @var ZuanzhanImgAggregate */
    public $zuanzhanImgAggregate;
    /** @var ZuanzhanImgDto */
    public $zuanzhanImgDto;
    /** @var ZuanzhanImgDto */
    public $zuanzhanImgForm;

    public function __construct($id, $module,
                                ZuanzhanImgAggregate $zuanzhanImgAggregate,
                                ZuanzhanImgForm $zuanzhanImgForm,
                                ZuanzhanImgDto $zuanzhanImgDto
        ,$config = [])
    {
        $this->zuanzhanImgAggregate = $zuanzhanImgAggregate;
        $this->zuanzhanImgForm       = $zuanzhanImgForm;
        $this->zuanzhanImgDto      = $zuanzhanImgDto;
        parent::__construct($id, $module, $config);
    }

    public function verbs():array
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
     * @params string $actionName
     * @return model
     * @throws Exception
     * @author ctl
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName){
            case 'actionIndex';
                return $this->zuanzhanImgDto->setScenario(ZuanzhanImgDto::SEARCH);
            case 'actionCreate':
                return $this->zuanzhanImgForm;
            case 'actionUpdate':
                return $this->zuanzhanImgForm;
            case 'actionDelete':
                return $this->zuanzhanImgDto;
            case 'actionAudit':
                return $this->zuanzhanImgDto->setScenario(ZuanzhanImgDto::AUDIT);
            case 'actionRead':
                return $this->zuanzhanImgDto->setScenario(ZuanzhanImgDto::READ);
            case 'actionDetail':
                return $this->zuanzhanImgDto;
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    public function actionIndex(): array
    {
        $data = $this->zuanzhanImgAggregate->listZuanzhanImg($this->zuanzhanImgDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->zuanzhanImgAggregate->createZuanzhanImg($this->zuanzhanImgForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->zuanzhanImgAggregate->detailZuanzhanImg((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->zuanzhanImgForm->imageFile->baseName.'.'.$this->zuanzhanImgForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->zuanzhanImgAggregate->updateZuanzhanImg($this->zuanzhanImgForm);
            return ['修改成功', 200,['picture_name'=>$this->zuanzhanImgForm->imageFile->baseName.'.'.$this->zuanzhanImgForm->imageFile->extension]];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    public function actionDelete(): array
    {
        $num = $this->zuanzhanImgAggregate->deleteZuanzhanImg((int)$this->zuanzhanImgDto->id);
        return ['删除成功', 200, $num];
    }

    public function actionAudit(): array
    {
        try {
            $result = $this->zuanzhanImgAggregate->auditZuanzhanImg($this->zuanzhanImgDto);
            $data = [];
            if ($result) {
                $data = $this->zuanzhanImgAggregate->detailZuanzhanImg((int)$this->zuanzhanImgDto->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = Yii::$app->request->getHostInfo().$this->zuanzhanImgAggregate->readZuanzhanImg((int)$this->zuanzhanImgDto->id);
            return ['查看成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

    public function actionDetail(): array
    {
        try {
            $result = $this->zuanzhanImgAggregate->detailZuanzhanImg((int)$this->zuanzhanImgDto->id);
            return ['查看成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }
}