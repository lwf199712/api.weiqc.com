<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\dto\DesignCenterHomeVideoForm;
use app\modules\v2\operateDept\domain\entity\DesignCenterHomeVideoEntity;
use app\modules\v2\operateDept\domain\dto\DesignCenterHomeVideoQuery;
use app\modules\v2\operateDept\domain\repository\DesignCenterHomeVideoDoManager;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Class DesignCenterHomeVideoController
 * @package app\modules\v2\operateDept\rest
 */
class DesignCenterHomeVideoController extends AdminBaseController
{
    /** @var $designCenterHomeVideoForm */
    public $designCenterHomeVideoForm;
    /** @var $designCenterHomeVideoEntity */
    public $designCenterHomeVideoEntity;
    /** @var $designCenterHomeVideoQuery */
    public $designCenterHomeVideoQuery;
    /** @var $designCenterHomeVideoDoManager */
    public $designCenterHomeVideoDoManager;

    public function __construct($id, $module,
                                DesignCenterHomeVideoEntity $designCenterHomeVideoEntity,
                                DesignCenterHomeVideoForm $designCenterHomeVideoForm,
                                DesignCenterHomeVideoQuery $designCenterHomeVideoQuery,
                                DesignCenterHomeVideoDoManager $designCenterHomeVideoDoManager
                                ,$config = [])
    {
        $this->designCenterHomeVideoEntity = $designCenterHomeVideoEntity;
        $this->designCenterHomeVideoForm = $designCenterHomeVideoForm;
        $this->designCenterHomeVideoQuery = $designCenterHomeVideoQuery;
        $this->designCenterHomeVideoDoManager = $designCenterHomeVideoDoManager;
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
     * ????????????
     * Date: 2019/10/30
     * Author: ctl
     * @param string $actionName
     * @return Model
     * @throws \yii\base\Exception
     */
    public function  dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->designCenterHomeVideoQuery;
            case 'actionDelete':
            case 'actionCreate':
            case 'actionAudit' :
            case 'actionRead' :
            case 'actionDetail' :
            case 'actionUpdate' :
            return $this->designCenterHomeVideoForm;
            default:
                throw new HttpException('UnKnow ActionName ');
        }
    }

    /**
     * ??????????????????????????????
     * Date: 2019/10/31
     * Author: ctl
     * @return array
     */
    public function actionIndex(): array
    {
        try{
            $data['list'] = $this->designCenterHomeVideoDoManager->listDataProvider($this->designCenterHomeVideoQuery)->getModels();
            $data['totalCount'] = $this->designCenterHomeVideoDoManager->listDataProvider($this->designCenterHomeVideoQuery)->getTotalCount();
            return ['??????????????????',200,$data];
        }catch (Exception $exception){
            return ['??????????????????',500,'msg'=>$exception->getMessage()];
        }
    }

    /**
     * ????????????????????????
     * Date: 2019/10/30
     * Author: ctl
     * @return array
     */
    public function actionCreate(): array
    {
        try{
            // ???????????? ????????????????????????
            $url = $this->designCenterHomeVideoForm->uploadVideo();
            if ($url === false){
                return  ['????????????,??????????????????',500];
            }
            if (!$this->designCenterHomeVideoForm->category)
            {
                return  ['????????????,??????????????????',500];
            }
            $data = [];
            $this->designCenterHomeVideoForm->video = Yii::$app->request->getHostInfo().$url;
            $imageObj = UploadedFile::getInstanceByName('imageFile');
            if ($imageObj) {
                $imageUrl = $this->designCenterHomeVideoForm->uploadImage();
                $this->designCenterHomeVideoForm->thumbnail = Yii::$app->request->getHostInfo().$imageUrl;
            }
            $res = $this->designCenterHomeVideoEntity->createEntity($this->designCenterHomeVideoForm);
            if ($res){
                $id = Yii::$app->db->getLastInsertID();
                $data['list'] = $this->designCenterHomeVideoDoManager->detailData((int)$id)->attributes;
                return ['????????????',200,$data];
            }
        }catch (Exception $exception){
            return  ['????????????',500,'msg'=>$exception->getMessage()];
        }
    }

    /**
     * ??????????????????????????????
     * Date: 2019/10/31
     * Author: ctl
     */
    public function actionDelete(): ?array
    {
        try{
            $row = $this->designCenterHomeVideoEntity->deleteEntity($this->designCenterHomeVideoForm);
            if ($row){
                return ['????????????',200];
            }else{
                return['????????????',500,'msg'=>'??????????????????ID'];
            }
        }catch (Exception $exception){
            return['????????????',500,$exception->getMessage()];
        }
    }

    /**
     * ??????????????????????????????
     * Date: 2019/11/1
     * Author: ctl
     */
    public function actionUpdate()
    {
        $data = [];
        try{
            if ($this->designCenterHomeVideoForm->videoFile) {
                // ???????????? ????????????????????????
                $url = Yii::$app->request->getHostInfo() . $this->designCenterHomeVideoForm->uploadVideo();
                // ????????????????????????
                $old_url = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes['video'];
                // ???????????????
                $this->designCenterHomeVideoForm->video = $url;
                $res = $this->designCenterHomeVideoEntity->updateEntity($this->designCenterHomeVideoForm, $old_url);
            }
            $imageObj = UploadedFile::getInstanceByName('imageFile');
            $oldImageUrl = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes['thumbnail'];
            if ($imageObj) {
                $imageUrl = Yii::$app->request->getHostInfo() . $this->designCenterHomeVideoForm->uploadImage();
                $this->designCenterHomeVideoForm->thumbnail = $imageUrl;
            }
            if ($oldImageUrl) {
                $res = $this->designCenterHomeVideoEntity->updateEntity($this->designCenterHomeVideoForm, $oldImageUrl);
            } else {
                $res = $this->designCenterHomeVideoEntity->updateEntity($this->designCenterHomeVideoForm);
            }
            $data['list'] = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes;
            if ($res){
                return ['????????????',200,$data];
            }
        }catch (Exception $exception){
            return['????????????',500,$exception->getMessage()];
        }
    }

    /**
     * ??????????????????????????????
     * Date: 2019/10/31
     * Author: ctl
     */
    public function actionAudit(): array
    {
        try{
            $res = $this->designCenterHomeVideoEntity->auditEntity($this->designCenterHomeVideoForm);
            $data = [];
            if ($res){
                $data = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes;
            }
            return ['????????????',200,['audit_status'=>$data['audit_status'],'audit_opinion'=>$data['audit_opinion'],'auditor'=>$data['auditor'],'audit_time'=>$data['audit_time']]];
        }catch (Exception $exception){
            return ['????????????',500,$exception->getMessage()];
        }
    }

    /**
     * ????????????
     * Date: 2019/10/31
     * Author: ctl
     * @return array
     * @throws Exception
     */
    public function actionRead(): array
    {
        $url = $this->designCenterHomeVideoEntity->urlEntity($this->designCenterHomeVideoForm);
        return ['??????????????????',200,$data['url']=$url];
    }

    /**
     * ????????????????????????
     * Date: 2019/10/31
     * Author: ctl
     * @return array
     */
    public function actionDetail(): array
    {
        try{
            $data = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes;
            $res = [];
            if ($data){
                return ['????????????',200,$data];
            }
            return ['??????????????????',500];
        }catch (Exception $exception){
            return ['????????????',500,$exception->getMessage()];
        }
    }
}