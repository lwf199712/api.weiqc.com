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
     * 数据映射
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
     * 查看设计中心主图视频
     * Date: 2019/10/31
     * Author: ctl
     * @return array
     */
    public function actionIndex() :array
    {
        try{
            $data = $this->designCenterHomeVideoDoManager->listDataProvider($this->designCenterHomeVideoQuery)->getModels();
            $data['totalCount'] = $this->designCenterHomeVideoDoManager->listDataProvider($this->designCenterHomeVideoQuery)->getTotalCount();
            return ['成功返回数据',200,$data];
        }catch (Exception $exception){
            return ['查询数据失败',500,'msg'=>$exception->getMessage()];
        }
    }

    /**
     * 设计中心主图视频
     * Date: 2019/10/30
     * Author: ctl
     * @return array
     */
    public function actionCreate() :array
    {
        try{
            // 上传视频 并返回视频的地址
            $url = $this->designCenterHomeVideoForm->uploadVideo();
            if ($url === false){
                return  ['插入失败',500,'msg'=>'视频不能为空'];
            }
            $data = [];
            $this->designCenterHomeVideoForm->video = Yii::$app->request->getHostInfo().$url;
            $res = $this->designCenterHomeVideoEntity->createEntity($this->designCenterHomeVideoForm);
            if ($res){
                $id = Yii::$app->db->getLastInsertID();
                $data['list'] = $this->designCenterHomeVideoDoManager->detailData((int)$id)->attributes;
                return ['插入成功',200,$data];
            }
        }catch (Exception $exception){
            return  ['插入失败',500,'msg'=>$exception->getMessage()];
        }
    }

    /**
     * 删除设计中心主图视频
     * Date: 2019/10/31
     * Author: ctl
     */
    public function actionDelete(): ?array
    {
        try{
            $row = $this->designCenterHomeVideoEntity->deleteEntity($this->designCenterHomeVideoForm);
            if ($row){
                return ['删除成功',200];
            }else{
                return['删除失败',500,'msg'=>'不存在的视频ID'];
            }
        }catch (Exception $exception){
            return['删除失败',500,$exception->getMessage()];
        }
    }

    /**
     * 更新设计中心主图视频
     * Date: 2019/11/1
     * Author: ctl
     */
    public function actionUpdate()
    {
        $data = [];
        try{
            // 上传视频 并返回视频的地址
            $url = Yii::$app->request->getHostInfo().$this->designCenterHomeVideoForm->uploadVideo();
            // 获取旧的视频地址
            $old_url = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes['video'];
            // 删除旧视频
            $this->designCenterHomeVideoForm->video = $url;
            $res = $this->designCenterHomeVideoEntity->updateEntity($this->designCenterHomeVideoForm,$old_url);
            $data['list'] = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes;
            if ($res){
                return ['更新成功',200,$data];
            }
        }catch (Exception $exception){
            return['更新失败',500,$exception->getMessage()];
        }
    }

    /**
     * 设计中心主图视频审核
     * Date: 2019/10/31
     * Author: ctl
     */
    public function actionAudit() :array
    {
        try{
            $res = $this->designCenterHomeVideoEntity->auditEntity($this->designCenterHomeVideoForm);
            $data = [];
            if ($res){
                $data = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes;
            }
            return ['审核通过',200,['audit_status'=>$data['audit_status'],'audit_opinion'=>$data['audit_opinion'],'auditor'=>$data['auditor'],'audit_time'=>$data['audit_time']]];
        }catch (Exception $exception){
            return ['审核失败',500,$exception->getMessage()];
        }
    }

    /**
     * 查看视频
     * Date: 2019/10/31
     * Author: ctl
     * @return array
     * @throws Exception
     */
    public function actionRead() :array
    {
        $url = $this->designCenterHomeVideoEntity->urlEntity($this->designCenterHomeVideoForm);
        return ['查看视频成功',200,$data['url']=$url];
    }

    /**
     * 查看主图视频详情
     * Date: 2019/10/31
     * Author: ctl
     * @return array
     */
    public function actionDetail() :array
    {
        try{
            $data = $this->designCenterHomeVideoDoManager->detailData((int)$this->designCenterHomeVideoForm->id)->attributes;
            $res = [];
            if ($data){
                return ['查看成功',200,$data];
            }
            return ['查看数据为空',500];
        }catch (Exception $exception){
            return ['查看失败',500,$exception->getMessage()];
        }
    }
}