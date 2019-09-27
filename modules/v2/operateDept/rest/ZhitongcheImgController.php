<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\ZhitongcheImgAggregate;
use app\modules\v2\operateDept\domain\dto\ZhitongcheImgDto;
use app\modules\v2\operateDept\domain\dto\ZhitongcheImgForm;
use Exception;
use Yii;
use yii\base\Model;

class ZhitongcheImgController extends AdminBaseController
{
    public $dto;

    /** @var ZhitongcheImgAggregate */
    public $zhitongcheImgAggregate;
    /** @var ZhitongcheImgDto */
    public $zhitongcheImgDto;
    /** @var ZhitongcheImgDto */
    public $zhitongcheImgForm;

    public function __construct($id, $module,
                                ZhitongcheImgAggregate $zhitongcheImgAggregate,
                                ZhitongcheImgForm $zhitongcheImgForm,
                                ZhitongcheImgDto $zhitongcheImgDto
        ,$config = [])
    {
        $this->zhitongcheImgAggregate = $zhitongcheImgAggregate;
        $this->zhitongcheImgForm       = $zhitongcheImgForm;
        $this->zhitongcheImgDto      = $zhitongcheImgDto;
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
                return $this->zhitongcheImgDto->setScenario(ZhitongcheImgDto::SEARCH);
            case 'actionCreate':
                return $this->zhitongcheImgForm;
            case 'actionUpdate':
                return $this->zhitongcheImgForm;
            case 'actionDelete':
                return $this->zhitongcheImgDto;
            case 'actionAudit':
                return $this->zhitongcheImgDto->setScenario(ZhitongcheImgDto::AUDIT);
            case 'actionRead':
                return $this->zhitongcheImgDto->setScenario(ZhitongcheImgDto::READ);
            case 'actionDetail':
                return $this->zhitongcheImgDto;
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    public function actionIndex(): array
    {
        $data = $this->zhitongcheImgAggregate->listZhitongcheImg($this->zhitongcheImgDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->zhitongcheImgAggregate->createZhitongcheImg($this->zhitongcheImgForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->zhitongcheImgAggregate->detailZhitongcheImg((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->zhitongcheImgForm->imageFile->baseName.'.'.$this->zhitongcheImgForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->zhitongcheImgAggregate->updateZhitongcheImg($this->zhitongcheImgForm);
            return ['修改成功', 200,['picture_name'=>$this->zhitongcheImgForm->imageFile->baseName.'.'.$this->zhitongcheImgForm->imageFile->extension]];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    public function actionDelete(): array
    {
        $num = $this->zhitongcheImgAggregate->deleteZhitongcheImg((int)$this->zhitongcheImgDto->id);
        return ['删除成功', 200, $num];
    }

    public function actionAudit(): array
    {
        try {
            $result = $this->zhitongcheImgAggregate->auditZhitongcheImg($this->zhitongcheImgDto);
            $data = [];
            if ($result) {
                $data = $this->zhitongcheImgAggregate->detailZhitongcheImg((int)$this->zhitongcheImgDto->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = Yii::$app->request->getHostInfo().$this->zhitongcheImgAggregate->readZhitongcheImg((int)$this->zhitongcheImgDto->id);
            return ['查看成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

    public function actionDetail(): array
    {
        try {
            $result = $this->zhitongcheImgAggregate->detailZhitongcheImg((int)$this->zhitongcheImgDto->id);
            return ['查看成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }
}