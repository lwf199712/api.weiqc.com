<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\entity;
use app\models\dataObject\PhysicalSendStatusDo;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusForm;
use yii\db\Exception;

class PhysicalSendStatusEntity extends PhysicalSendStatusDo
{
    /**
     * 更新实体
     * @param PhysicalSendStatusForm $physicalSendStatusForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateEntity(PhysicalSendStatusForm $physicalSendStatusForm): bool
    {
        $model = self::findOne($physicalSendStatusForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($physicalSendStatusForm->getAttributes());
        return $model->save();
    }

    /**
     * 删除实体
     * @param PhysicalSendStatusForm $physicalSendStatusForm
     * @return int
     * @author weifeng
     */

    public function deleteEntity(PhysicalSendStatusForm $physicalSendStatusForm)
    {
        /** @var PhysicalSendStatusForm $model */
        return self::deleteAll(['id' => $physicalSendStatusForm->id]);
    }
}