<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\aggregate\IndexImgAggregate;
use app\modules\v2\operateDept\domain\dto\IndexImgDto;
use app\modules\v2\operateDept\domain\dto\IndexImgForm;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class DesignCenterController
 * @property-read IndexImgAggregate $indexImgAggregate
 * @property IndexImgDto $indexImgDto
 * @property IndexImgForm $indexImgForm
 * @package app\modules\v2\operateDept\rest
 */

class IndexImgController extends AdminBaseController
{
    public $dto;

    /** @var IndexImgAggregate */
    public $indexImgAggregate;
    /** @var IndexImgDto */
    public $indexImgDto;
    /** @var IndexImgDto */
    public $indexImgForm;

    public function __construct($id, $module,
                                IndexImgAggregate $indexImgAggregate,
                                IndexImgForm $indexImgForm,
                                IndexImgDto $indexImgDto
                                ,$config = [])
    {
        $this->indexImgAggregate = $indexImgAggregate;
        $this->indexImgForm       = $indexImgForm;
        $this->indexImgDto      = $indexImgDto;
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
                return $this->indexImgDto->setScenario(IndexImgDto::SEARCH);
            case 'actionCreate':
                return $this->indexImgForm;
            case 'actionUpdate':
                return $this->indexImgForm;
            case 'actionDelete':
                return $this->indexImgDto;
            case 'actionAudit':
                return $this->indexImgDto->setScenario(IndexImgDto::AUDIT);
            case 'actionRead':
                return $this->indexImgDto->setScenario(IndexImgDto::READ);
            case 'actionDetail':
                return $this->indexImgDto;
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    public function actionIndex(): array
    {
        $data = $this->indexImgAggregate->listIndexImg($this->indexImgDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionCreate(): array
    {
        try {
            $result = $this->indexImgAggregate->createIndexImg($this->indexImgForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->indexImgAggregate->detailIndexImg((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->indexImgForm->imageFile->baseName.'.'.$this->indexImgForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->indexImgAggregate->updateIndexImg($this->indexImgForm);
            return ['修改成功', 200,['picture_name'=>$this->indexImgForm->imageFile->baseName.'.'.$this->indexImgForm->imageFile->extension]];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    public function actionDelete(): array
    {
        $num = $this->indexImgAggregate->deleteIndexImg((int)$this->indexImgDto->id);
        return ['删除成功', 200, $num];
    }

    public function actionAudit(): array
    {
        try {
            $result = $this->indexImgAggregate->auditIndexImg($this->indexImgDto);
            $data = [];
            if ($result) {
                $data = $this->indexImgAggregate->detailIndexImg((int)$this->indexImgDto->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    public function actionRead(): array
    {
        try {
            $imgUrl = Yii::$app->request->getHostInfo().$this->indexImgAggregate->readIndexImg((int)$this->indexImgDto->id);
            return ['查看图片成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看图片失败', 500, $exception->getMessage()];
        }
    }

    public function actionDetail(): array
    {
        try {
            $result = $this->indexImgAggregate->detailIndexImg((int)$this->indexImgDto->id);
            return ['查看详情成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看详情失败', 500, $exception->getMessage()];
        }
    }
}