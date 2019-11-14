<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\DesignCenterVideoDo;
use app\models\User;
use app\modules\v2\operateDept\domain\dto\DesignCenterHomeVideoForm;
use app\modules\v2\operateDept\domain\repository\DesignCenterHomeVideoDoManager;
use Yii;
use yii\db\Exception;

class DesignCenterHomeVideoEntity extends DesignCenterVideoDo
{
    /**
     * 创建主图视频实体
     * Date: 2019/10/30
     * Author: ctl
     * @param DesignCenterHomeVideoForm $designCenterHomeVideoForm
     * @return bool
     */
    public function createEntity(DesignCenterHomeVideoForm $designCenterHomeVideoForm):bool
    {
        $model = new self();
        $model->setAttributes($designCenterHomeVideoForm->getAttributes());
        $model->video = $designCenterHomeVideoForm->video;
        $model->upload_time = time();
        $model->audit_status    = 0;
        return $model->save();
    }

    /**
     * 删除主图视频实体
     * Date: 2019/10/31
     * Author: ctl
     * @param DesignCenterHomeVideoForm $designCenterHomeVideoForm
     * @return int
     * @throws \Exception
     */
    public function deleteEntity(DesignCenterHomeVideoForm $designCenterHomeVideoForm) :int
    {
        if ($designCenterHomeVideoForm->id === null){
            throw new \RuntimeException('删除ID不能为空');
        }

        // 删除本地上的文件
        $address = self::findOne(['id' => $designCenterHomeVideoForm->id])->video;
        $res = $designCenterHomeVideoForm->deletelocal($address);
        if ($res){
            return self::deleteAll(['id' => $designCenterHomeVideoForm->id]);
        }
        return 0;
    }

    /**
     * 审核主图视频实体
     * Date: 2019/10/31
     * Author: ctl
     * @param DesignCenterHomeVideoForm $designCenterHomeVideoForm
     * @return bool
     * @throws \Exception
     */
    public function auditEntity(DesignCenterHomeVideoForm $designCenterHomeVideoForm) :bool
    {
        if (!$designCenterHomeVideoForm->id){
            throw new \RuntimeException('审核id不能为空');
        }
        $model = new self();
        $model = $model::findOne(['id'=>$designCenterHomeVideoForm->id]);
        $model->audit_status = $designCenterHomeVideoForm->audit_status;
        $model->audit_opinion = $designCenterHomeVideoForm->audit_opinion;
        $model->audit_time = time();
        $model->auditor = User::findOne(['id'=>Yii::$app->user->getId()])->username;
        return $model->save();
    }

    /**
     * 查看主图视频的url实体
     * Date: 2019/10/31
     * Author: ctl
     * @param DesignCenterHomeVideoForm $designCenterHomeVideoForm
     * @return string
     * @throws \Exception
     */
    public function urlEntity(DesignCenterHomeVideoForm $designCenterHomeVideoForm):string
    {
        if (!$designCenterHomeVideoForm->id){
            throw new \RuntimeException('视频id不能为空');
        }
        return self::findOne(['id'=>$designCenterHomeVideoForm->id])->video;
    }

    /**
     * 查看主图视频详情实体
     * Date: 2019/10/31
     * Author: ctl
     * @param DesignCenterHomeVideoForm $designCenterHomeVideoForm
     * @return array
     * @throws \Exception
     */
    public function detailEntity(DesignCenterHomeVideoForm $designCenterHomeVideoForm):array
    {
        if (!$designCenterHomeVideoForm->id){
            throw new \RuntimeException('查看id不能为空');
        }

    }

    /**
     * Date: 2019/11/1
     * Author: ctl
     * @param DesignCenterHomeVideoForm $designCenterHomeVideoForm
     * @param string $old_url
     * @return bool
     */
    public function updateEntity(DesignCenterHomeVideoForm $designCenterHomeVideoForm,string $old_url):bool
    {
        $model = new self();
        // 判断有没有上传新的视频
        if ($designCenterHomeVideoForm->video){
            // 先删除旧视频
            $address = self::findOne(['id' => $designCenterHomeVideoForm->id])->video;
            $designCenterHomeVideoForm->deletelocal($old_url);
        }
        $model = $model::findOne(['id'=>$designCenterHomeVideoForm->id]);
        $arr  = $designCenterHomeVideoForm->getAttributes();
        // 如果为空就删除 不执行更新
        foreach ($arr as $k=>$v){
                if ($v === null){
                    unset($arr[$k]);
                }
        }
        $model->setAttributes($arr);
        $model->upload_time     = time();
        $model->audit_status    = 0;
        return $model->save();
    }
}