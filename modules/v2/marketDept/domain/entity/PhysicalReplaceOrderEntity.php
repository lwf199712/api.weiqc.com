<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\entity;

use app\models\dataObject\PhysicalReplaceOrderDo;
use app\models\User;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderDto;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderForm;
use Yii;
use yii\db\Exception;

class PhysicalReplaceOrderEntity extends PhysicalReplaceOrderDo
{
    /**
     * 更新实物置换订单实体
     * @param PhysicalReplaceOrderForm $physicalReplaceOrderForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateEntity(PhysicalReplaceOrderForm $physicalReplaceOrderForm): bool
    {
        $model = self::findOne($physicalReplaceOrderForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($physicalReplaceOrderForm->getAttributes());
        //如果初审为已通过和未通过，终审为待审核和未通过，重新编辑后初终审为待审核
        if ($model->first_trial !== 0 && $model->final_judgment !== 1) {
            $model->first_trial = 0;
            $model->final_judgment = 0;
        }
        if ($model->first_trial === 1 && $model->final_judgment === 1){
            throw new Exception('初审和终审已通过，不可编辑！！！');
        }
        $model->dispatch_time = strtotime($physicalReplaceOrderForm->dispatch_time);
        return $model->save();
    }

    /**
     * 删除实物置换订单实体
     * @param PhysicalReplaceOrderDto $physicalReplaceOrderDto
     * @return int
     * @author weifeng
     */

    public function deleteEntity(PhysicalReplaceOrderDto $physicalReplaceOrderDto)
    {
        /** @var PhysicalReplaceOrderForm $model */
        return self::deleteAll(['id' => $physicalReplaceOrderDto->id]);
    }

    /**
     * 审核实物置换订单实体
     * @param PhysicalReplaceOrderDto $physicalReplaceOrderDto
     * @return bool
     * @author weifeng
     */
    public function auditEntity(PhysicalReplaceOrderDto $physicalReplaceOrderDto): bool
    {
        $id = Yii::$app->user->getId();
        /** @var User $user */
        $user = User::findOne(['id' => $id]);

        $model = self::findOne($physicalReplaceOrderDto->id);
        if ($physicalReplaceOrderDto->first_trial){
            $model->first_trial         = $physicalReplaceOrderDto->first_trial;        //初审状态
            $model->first_audit_opinion = $physicalReplaceOrderDto->first_audit_opinion;//初审审核意见
            $model->first_auditor       = $user->realname;                              //初审人
        }
        if ($physicalReplaceOrderDto->final_judgment){
            $model->final_judgment      = $physicalReplaceOrderDto->final_judgment;     //终审状态
            $model->final_audit_opinion = $physicalReplaceOrderDto->final_audit_opinion;//终审审核意见
            $model->final_auditor       = $user->realname;                              //终审人
        }
        return $model->save();
    }


}