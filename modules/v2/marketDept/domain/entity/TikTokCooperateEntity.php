<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\entity;

use app\models\dataObject\TikTokCooperateDo;
use app\modules\v2\marketDept\domain\dto\TikTokCooperateDto;
use app\modules\v2\marketDept\domain\dto\TikTokCooperatePersonalInfoForm;
use yii\db\Exception;

class TikTokCooperateEntity extends TikTokCooperateDo
{

    /**
     * 创建抖音合作审核实体
     * @param TikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm
     * @return bool
     * @author zhuozhen
     */
    public function createEntity(tikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm) : bool
    {
        $model = new self;
        $model->setAttributes($tikTokCooperatePersonalInfoForm->getAttributes());
        return $model->save();
    }

    /**
     * 更新抖音合作审核实体
     * @param TikTokCooperateDto $tikTokCooperateDto
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateEntity(TikTokCooperateDto $tikTokCooperateDto) : bool
    {
        $model = self::findOne($tikTokCooperateDto->id);
        if ($model === null){
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($tikTokCooperateDto->getAttributes());
        return $model->save();
    }

    /**
     * 删除抖音合作审核实体
     * @param int $id
     * @return int
     * @author zhuozhen
     */
    public function deleteEntity(int $id) : int
    {
        return self::deleteAll(['id' => $id]);
    }
}