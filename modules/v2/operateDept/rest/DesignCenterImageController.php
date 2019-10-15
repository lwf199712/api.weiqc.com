<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageQuery;
use app\modules\v2\operateDept\service\DesignCenterImageService;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

class DesignCenterImageController extends AdminBaseController
{
    /** @var DesignCenterImageForm */
    public $designCenterImageForm;
    /** @var DesignCenterImageQuery */
    public $designCenterImageQuery;
    /** @var DesignCenterImageService */
    public $designCenterImageService;

    public function __construct($id, $module,
                                DesignCenterImageQuery      $designCenterImageQuery,
                                DesignCenterImageForm       $designCenterImageForm,
                                DesignCenterImageService    $designCenterImageService,
                                $config = [])
    {
        $this->designCenterImageForm    = $designCenterImageForm;
        $this->designCenterImageQuery   = $designCenterImageQuery;
        $this->designCenterImageService = $designCenterImageService;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'     => ['GET', 'HEAD', 'OPTIONS'],
            'create'    => ['POST', 'OPTIONS'],
            'update'    => ['POST', 'OPTIONS'],
            'delete'    => ['DELETE', 'OPTIONS'],
            'audit'     => ['POST', 'OPTIONS'],
            'read'      => ['GET', 'HEAD', 'OPTIONS'],
            'detail'    => ['GET', 'HEAD', 'OPTIONS'],
        ];
    }

    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @throws Exception
     * @author: weifeng
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->designCenterImageQuery;
            case 'actionCreate':
                return $this->designCenterImageForm;
            case 'actionUpdate':
                return $this->designCenterImageForm;
            case 'actionDelete':
                return $this->designCenterImageForm;
            case 'actionAudit':
                return $this->designCenterImageForm;
            case 'actionRead':
                return $this->designCenterImageForm;
            case 'actionDetail':
                return $this->designCenterImageForm;
            default:
                throw new HttpException('UnKnow ActionName ');
        }
    }

    /**
     * 设计中心-首页
     * @return array
     * @author: weifeng
     */
    public function actionIndex(): array
    {
        try {
            $data = $this->designCenterImageService->listImage($this->designCenterImageQuery);
            return ['成功返回数据', 200, $data];
        } catch (Exception $exception) {
            return ['查询失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心-新增
     * @return array
     * @author: weifeng
     */
    public function actionCreate(): array
    {
        try {
            //上传图片
            $imagePath = $this->designCenterImageService->uploadImage($this->designCenterImageForm, $this->designCenterImageForm->type);
            //获取图片地址
            $this->designCenterImageForm->picture_address = $imagePath;
            $result = $this->designCenterImageService->createImage($this->designCenterImageForm);
            $data = [];
            if ($result) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['lists'] = $this->designCenterImageService->viewImage((int)$data['id']);
                $data['lists']['picture_address'] = Yii::$app->request->getHostInfo() . $data['lists']['picture_address'];
                $data['lists']['picture_name'] = $this->designCenterImageForm->imageFile->baseName . '.' . $this->designCenterImageForm->imageFile->extension;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心-更新
     * @return array
     * @author: weifeng
     */
    public function actionUpdate(): array
    {
        try {
            //上传图片
            $imagePath = $this->designCenterImageService->uploadImage($this->designCenterImageForm, $this->designCenterImageForm->type);
            //获取图片地址
            $this->designCenterImageForm->picture_address = $imagePath;
            $res = $this->designCenterImageService->updateImage($this->designCenterImageForm);
            //上传成功删除旧图片
            if ($res) {
                //根据路径删除图片文件
                $dePath = $this->designCenterImageService->viewImage((int)$this->designCenterImageForm->id);
                unlink(Yii::$app->basePath . '/web' . $dePath['picture_address']);
            }
            return ['修改成功', 200, ['picture_name' => $this->designCenterImageForm->imageFile->baseName . '.' . $this->designCenterImageForm->imageFile->extension]];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心-删除
     * @return array
     * @author: weifeng
     */
    public function actionDelete(): array
    {
        $num = $this->designCenterImageService->deleteImage($this->designCenterImageForm);
        return ['删除成功', 200, $num];
    }

    /**
     * 设计中心-审核
     * @return array
     * @author: weifeng
     */
    public function actionAudit(): array
    {
        try {
            $result = $this->designCenterImageService->auditImage($this->designCenterImageForm);
            $data = [];
            if ($result) {
                $data = $this->designCenterImageService->viewImage((int)$this->designCenterImageForm->id);
            }
            return ['审核成功', 200, ['audit_status' => $data['audit_status'], 'audit_opinion' => $data['audit_opinion'], 'auditor' => $data['auditor'], 'audit_time' => $data['audit_time']]];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心-查看
     * @return array
     * @author: weifeng
     */
    public function actionRead(): array
    {
        try {
            $readImage = $this->designCenterImageService->viewImage((int)$this->designCenterImageForm->id);
            $imgUrl = Yii::$app->request->getHostInfo() . $readImage['picture_address'];
            return ['查看成功', 200, $imgUrl];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心-详情
     * @return array
     * @author: weifeng
     */
    public function actionDetail(): array
    {
        try {
            $result = $this->designCenterImageService->viewImage((int)$this->designCenterImageForm->id);
            return ['查看成功', 200, $result];
        } catch (Exception $exception) {
            return ['查看失败', 500, $exception->getMessage()];
        }
    }
}