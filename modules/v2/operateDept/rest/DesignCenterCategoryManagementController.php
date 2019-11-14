<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\dto\DesignCenterCategoryManagementForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterCategoryManagementQuery;
use app\modules\v2\operateDept\domain\entity\DesignCenterCategoryManagementEntity;
use app\modules\v2\operateDept\domain\repository\DesignCenterCategoryManagementDoManager;
use Exception;
use http\Exception\RuntimeException;
use yii\base\Model;
use app\components\UploadFile;
use yii\web\HttpException;
use Yii;
use yii\web\UploadedFile;


/**
 * Class DesignCenterCategoryManagementController
 * @package app\modules\v2\operateDept\rest
 */
class DesignCenterCategoryManagementController extends AdminBaseController
{
    /** @var DesignCenterCategoryManagementForm */
    public $designCenterCategoryManagementForm;
    /** @var DesignCenterCategoryManagementQuery */
    public $designCenterCategoryManagementQuery;
    /** @var DesignCenterCategoryManagementEntity */
    public $designCenterCategoryManagementEntity;
    /** @var DesignCenterCategoryManagementDoManager */
    public  $designCenterCategoryManagementDoManager;
    public function __construct($id, $module,
                                DesignCenterCategoryManagementForm $designCenterCategoryManagementForm,
                                DesignCenterCategoryManagementQuery $designCenterCategoryManagementQuery,
                                DesignCenterCategoryManagementEntity $designCenterCategoryManagementEntity,
                                DesignCenterCategoryManagementDoManager $designCenterCategoryManagementDoManager
                                ,$config = [])
    {
        $this->designCenterCategoryManagementForm = $designCenterCategoryManagementForm ;
        $this->designCenterCategoryManagementQuery = $designCenterCategoryManagementQuery ;
        $this->designCenterCategoryManagementEntity = $designCenterCategoryManagementEntity ;
        $this->designCenterCategoryManagementDoManager = $designCenterCategoryManagementDoManager ;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'     => ['GET', 'HEAD', 'OPTIONS'],
            'create'    => ['POST', 'OPTIONS'],
            'update'    => ['POST', 'OPTIONS'],
            'delete'    => ['DELETE', 'OPTIONS'],
            'upload'    => ['POST', 'OPTIONS'],
        ];
    }

    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @throws Exception
     * @author: ctl
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->designCenterCategoryManagementQuery;
            case 'actionCreate':
                return $this->designCenterCategoryManagementForm;
            case 'actionUpdate':
                return $this->designCenterCategoryManagementForm;
            case 'actionDelete':
                return $this->designCenterCategoryManagementForm;
            case 'actionUpload':
                return $this->designCenterCategoryManagementForm;
            default:
                throw new HttpException('UnKnow ActionName ');
        }
    }

    /**
     * 设计中心查询属性
     * @return array
     * @author ctl
     */
    public function actionIndex() :array
    {
        try{
            $data = $this->designCenterCategoryManagementDoManager->listDataProvider($this->designCenterCategoryManagementQuery)->getModels();
            $data['totalCount'] = $this->designCenterCategoryManagementDoManager->listDataProvider($this->designCenterCategoryManagementQuery)->getTotalCount();
            var_dump($data);die();
            return ['成功返回数据', 200, $data];
        }catch (Exception $exception){
            return ['查询失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心属性新增
     * @return array
     * @author ctl
     */
    public function actionCreate() :array
    {
        try{
            $data = [];
            $res = $this->designCenterCategoryManagementEntity->createEntity($this->designCenterCategoryManagementForm);
            if ($res == true){
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['category'] = $this->designCenterCategoryManagementForm->category;
            }
            return ['新增成功',200,$data];
        }catch (Exception $exception){
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心属性修改
     * @return array
     * @author ctl
     */
    public function actionUpdate() :array
    {
        try{
            $res = $this->designCenterCategoryManagementEntity->updateEntity($this->designCenterCategoryManagementForm);
            if ($res === false){
                throw new Exception('修改属性失败');
            }
            $data = [];
            $data['category'] = $this->designCenterCategoryManagementForm->category;
            return ['修改成功',200,$data];
        }catch (Exception $exception){
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    /**
     * Date: 2019/10/29
     * Author: ctl
     * @throws Exception
     * @return array
     */
    public function actionDelete() :array
    {
        try{
            $id = $this->designCenterCategoryManagementEntity->deleteEntity($this->designCenterCategoryManagementForm);
            if ($id === false){
                throw new Exception('删除属性失败');
            }
            return ['删除成功',200];
        }catch (Exception $exception){
            return ['删除失败',500,$exception->getMessage()];
        }
    }

    /**
     * test aliyun oss upload
     * Date: 2019/10/30
     * Author: ctl
     * @return array
     */
    public function actionUpload() :array
    {
        $this->designCenterCategoryManagementForm->video = UploadedFile::getInstanceByName('video');
        $file['video'] = json_decode(json_encode($this->designCenterCategoryManagementForm->video),true);
        $file['video']['tmp_name'] = $file['video']['tempName'];
        unset($file['video']['tempName']);
        $upload = new UploadFile($file);
        try {
            $url = $upload->upload('oss', 'gdt/operateDept/video/');
            $data = ['url' => $url];
            return ['上传成功',200,$data];
        } catch (\OSS\Core\OssException $ossException) {
            return ['上传失败',500,$data = ['msg'=>$ossException->getMessage()]];
        } catch (\Exception $e) {
            return ['上传失败',500,$data = ['msg'=>$e->getMessage()]];
        }
    }
}