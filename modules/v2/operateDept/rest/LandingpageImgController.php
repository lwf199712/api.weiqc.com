<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\LandingpageImgAggregate;
use app\modules\v2\operateDept\domain\dto\LandingpageImgDto;
use app\modules\v2\operateDept\domain\dto\LandingpageImgForm;
use Exception;
use Yii;
use yii\base\Model;

class LandingpageImgController extends AdminBaseController
{
    public $dto;

    /** @var LandingpageImgAggregate */
    public $landingpageImgAggregate;
    /** @var LandingpageImgDto */
    public $landingpageImgDto;
    /** @var LandingpageImgDto */
    public $landingpageImgForm;

    public function __construct($id, $module,
                                LandingpageImgAggregate $landingpageImgAggregate,
                                LandingpageImgForm $landingpageImgForm,
                                LandingpageImgDto $landingpageImgDto
        ,$config = [])
    {
        $this->landingpageImgAggregate = $landingpageImgAggregate;
        $this->landingpageImgForm       = $landingpageImgForm;
        $this->landingpageImgDto      = $landingpageImgDto;
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
                return $this->landingpageImgDto->setScenario(LandingpageImgDto::SEARCH);
            case 'actionCreate':
                return $this->landingpageImgForm;
            case 'actionUpdate':
                return $this->landingpageImgForm;
            case 'actionDelete':
                return $this->landingpageImgDto;
            case 'actionAudit':
                return $this->landingpageImgDto->setScenario(LandingpageImgDto::AUDIT);
            case 'actionRead':
                return $this->landingpageImgDto->setScenario(LandingpageImgDto::READ);
            case 'actionDetail':
                return $this->landingpageImgDto;
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    public function actionIndex(): array
    {
        $data = $this->landingpageImgAggregate->listLandingpageImg($this->landingpageImgDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->landingpageImgAggregate->createLandingpageImg($this->landingpageImgForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->landingpageImgAggregate->detailLandingpageImg((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->landingpageImgForm->imageFile->baseName.'.'.$this->landingpageImgForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->landingpageImgAggregate->updateLandingpageImg($this->landingpageImgForm);
            return ['修改成功', 200,['picture_name'=>$this->landingpageImgForm->imageFile->baseName.'.'.$this->landingpageImgForm->imageFile->extension]];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    public function actionDelete(): array
    {
        $num = $this->landingpageImgAggregate->deleteLandingpageImg((int)$this->landingpageImgDto->id);
        return ['删除成功', 200, $num];
    }

    public function actionAudit(): array
    {
        try {
            $result = $this->landingpageImgAggregate->auditLandingpageImg($this->landingpageImgDto);
            $data = [];
            if ($result) {
                $data = $this->landingpageImgAggregate->detailLandingpageImg((int)$this->landingpageImgDto->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = Yii::$app->request->getHostInfo().$this->landingpageImgAggregate->readLandingpageImg((int)$this->landingpageImgDto->id);
            return ['查看成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

    public function actionDetail(): array
    {
        try {
            $result = $this->landingpageImgAggregate->detailLandingpageImg((int)$this->landingpageImgDto->id);
            return ['查看成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }
}