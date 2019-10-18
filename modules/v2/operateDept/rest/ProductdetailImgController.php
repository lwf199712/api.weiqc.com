<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\ProductdetailImgAggregate;
use app\modules\v2\operateDept\domain\dto\ProductdetailImgDto;
use app\modules\v2\operateDept\domain\dto\ProductdetailImgForm;
use Exception;
use Yii;
use yii\base\Model;

class ProductdetailImgController extends AdminBaseController
{
    public $dto;

    /** @var ProductdetailImgAggregate */
    public $productdetailImgAggregate;
    /** @var ProductdetailImgDto */
    public $productdetailImgDto;
    /** @var ProductdetailImgDto */
    public $productdetailImgForm;

    public function __construct($id, $module,
                                ProductdetailImgAggregate $productdetailImgAggregate,
                                ProductdetailImgForm $productdetailImgForm,
                                ProductdetailImgDto $productdetailImgDto
        ,$config = [])
    {
        $this->productdetailImgAggregate = $productdetailImgAggregate;
        $this->productdetailImgForm       = $productdetailImgForm;
        $this->productdetailImgDto      = $productdetailImgDto;
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
                return $this->productdetailImgDto->setScenario(ProductdetailImgDto::SEARCH);
            case 'actionCreate':
                return $this->productdetailImgForm;
            case 'actionUpdate':
                return $this->productdetailImgForm;
            case 'actionDelete':
                return $this->productdetailImgDto;
            case 'actionAudit':
                return $this->productdetailImgDto->setScenario(ProductdetailImgDto::AUDIT);
            case 'actionRead':
                return $this->productdetailImgDto->setScenario(ProductdetailImgDto::READ);
            case 'actionDetail':
                return $this->productdetailImgDto;
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    public function actionIndex(): array
    {
        $data = $this->productdetailImgAggregate->listProductdetailImg($this->productdetailImgDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->productdetailImgAggregate->createProductdetailImg($this->productdetailImgForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->productdetailImgAggregate->detailProductdetailImg((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->productdetailImgForm->imageFile->baseName.'.'.$this->productdetailImgForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->productdetailImgAggregate->updateProductdetailImg($this->productdetailImgForm);
            return ['修改成功', 200,['picture_name'=>$this->productdetailImgForm->imageFile->baseName.'.'.$this->productdetailImgForm->imageFile->extension]];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    public function actionDelete(): array
    {
        $num = $this->productdetailImgAggregate->deleteProductdetailImg((int)$this->productdetailImgDto->id);
        return ['删除成功', 200, $num];
    }

    public function actionAudit(): array
    {
        try {
            $result = $this->productdetailImgAggregate->auditProductdetailImg($this->productdetailImgDto);
            $data = [];
            if ($result) {
                $data = $this->productdetailImgAggregate->detailProductdetailImg((int)$this->productdetailImgDto->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = Yii::$app->request->getHostInfo().$this->productdetailImgAggregate->readProductdetailImg((int)$this->productdetailImgDto->id);
            return ['查看成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

    public function actionDetail(): array
    {
        try {
            $result = $this->productdetailImgAggregate->detailProductdetailImg((int)$this->productdetailImgDto->id);
            return ['查看成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }
}