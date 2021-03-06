<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\CategoryManagementDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterCategoryManagementForm;

class DesignCenterCategoryManagementEntity extends CategoryManagementDo
{
    /**
     * 创建设计中心属性实体
     * @param DesignCenterCategoryManagementForm $designCenterCategoryManagementForm
     * @return bool
     * @throws \Exception
     * @author ctl
     */
    public function createEntity(DesignCenterCategoryManagementForm $designCenterCategoryManagementForm): bool
    {
        $model = new self;
        $model->setAttributes($designCenterCategoryManagementForm->getAttributes());
        return $model->save();
    }

    /**
     * 更新设计中心属性实体
     * @param DesignCenterCategoryManagementForm $designCenterCategoryManagementForm
     * @return bool
     * @throws \Exception
     * @author ctl
     */
    public function updateEntity(DesignCenterCategoryManagementForm $designCenterCategoryManagementForm): bool
    {
        $model = self::findOne($designCenterCategoryManagementForm->id);
        if ($model == null){
            throw new \Exception('找不到要修改的属性');
        }
        $model->setAttributes($designCenterCategoryManagementForm->getAttributes());
        return $model->save();
    }

    /**
     * 删除设计中心的属性实体
     * @param DesignCenterCategoryManagementForm $designCenterCategoryManagementForm
     * @return bool
     * @throws \Exception
     * @author ctl
     */
    public function deleteEntity(DesignCenterCategoryManagementForm $designCenterCategoryManagementForm): int
    {
        if ($designCenterCategoryManagementForm->id == null){
            throw new \Exception('删除ID不能为空');
        }
        return self::deleteAll(['id' => $designCenterCategoryManagementForm->id]);
    }

    /**
     * 查看设计中心详情实体
     * 查看设计中心属性的详情
     * Date: 2019/11/20
     * Author: ctl
     * @param DesignCenterCategoryManagementForm $designCenterCategoryManagementForm
     * @return DesignCenterCategoryManagementEntity|null
     * @throws \Exception
     */
    public function detailEntity(DesignCenterCategoryManagementForm $designCenterCategoryManagementForm)
    {
        if ($designCenterCategoryManagementForm->id == null){
            throw new \Exception('查看ID不能为空');
        }
        return self::findOne(['id'=>$designCenterCategoryManagementForm->id]);
    }
}