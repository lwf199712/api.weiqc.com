<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\DesignCenterImageDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageForm;
use yii\db\Exception;

class DesignCenterImageEntity extends DesignCenterImageDo
{

    /**
     * 创建设计中心实体
     * @param DesignCenterImageForm $designCenterImageForm
     * @return bool
     * @throws \Exception
     * @author weifeng
     */
    public function createEntity(DesignCenterImageForm $designCenterImageForm): bool
    {
        $model = new self;
        $model->setAttributes($designCenterImageForm->getAttributes());
        $model->picture_address = '/uploads/designCenter/' . $model->picture_address;
        $model->upload_time     = time();
        $model->audit_status    = 0;
        return $model->save();
    }

    /**
     * 更新设计中心实体
     * @param DesignCenterImageForm $designCenterImageForm
     * @return bool
     * @throws Exception
     * @author: weifeng
     */

    public function updateEntity(DesignCenterImageForm $designCenterImageForm): bool
    {
        $model = self::findOne($designCenterImageForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($designCenterImageForm->getAttributes());
        $model->picture_address = '/uploads/designCenter/' . $model->picture_address;
        $model->upload_time     = time();
        $model->audit_status    = 0;
        return $model->save();
    }

    /**
     * 删除设计中心实体
     * @param DesignCenterImageForm $designCenterImageForm
     * @return int
     * @author: weifeng
     */
    public function deleteEntity(DesignCenterImageForm $designCenterImageForm): int
    {
        /** @var DesignCenterImageForm $model */
        return self::deleteAll(['id' => $designCenterImageForm->id]);
    }

    /**
     * 审核设计中心实体
     * @param DesignCenterImageForm $designCenterImageForm
     * @return bool
     * @author: weifeng
     */

    public function auditEntity(DesignCenterImageForm $designCenterImageForm): bool
    {
        /** @var DesignCenterImageDo $model */
        $model = self::findOne($designCenterImageForm->id);
        $model->audit_status    = $designCenterImageForm->audit_status;
        $model->audit_opinion   = $designCenterImageForm->audit_opinion;
        $model->audit_time      = time();
        return $model->save();
    }
}