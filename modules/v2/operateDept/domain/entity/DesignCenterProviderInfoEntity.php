<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\DesignCenterProviderInfoDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoForm;
use RuntimeException;

class DesignCenterProviderInfoEntity extends DesignCenterProviderInfoDo
{
    /**
     * 创建设计中心实体
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     * @author weifeng
     */
    public function createEntity(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool
    {
        $model = new self;
        $model->setAttributes($designCenterProviderInfoForm->getAttributes());
        return $model->save();
    }

    /**
     * 编辑设计中心实体
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     * @author weifeng
     */
    public function updateEntity(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool
    {
        $model = self::findOne($designCenterProviderInfoForm->id);
        if ($model === null) {
            throw new RuntimeException('找不到修改的数据');
        }
        $model->setAttributes($designCenterProviderInfoForm->getAttributes());
        return $model->save();
    }

    /**
     * 删除设计中心实体
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return int
     * @author weifeng
     */
    public function deleteEntity(DesignCenterProviderInfoForm $designCenterProviderInfoForm): int
    {
        /** @var DesignCenterProviderInfoForm $model */
        return self::deleteAll(['id' => $designCenterProviderInfoForm->id]);
    }
}
