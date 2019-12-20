<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\common\exception\ApiException;
use app\models\dataObject\DesignCenterProviderInfoDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoForm;
use RuntimeException;

class DesignCenterProviderInfoEntity extends DesignCenterProviderInfoDo
{
    /**
     * 创建设计中心实体
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     * @throws ApiException
     * @author weifeng
     */
    public function createEntity(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool
    {
        $mustArr = ['name' => '名称', 'quoted_price' => '报价', 'site' => '地点', 'recommended_reason' => '推荐理由', 'contact_way' => '联系方式'];
        $tipsArr = [];
        foreach ($designCenterProviderInfoForm->getAttributes() as $k => $v) {
            if (empty($v) && array_key_exists($k, $mustArr)) {
                $tipsArr[] = $mustArr[$k];
            }
        }
        if ($tipsArr) {
            throw new ApiException(implode(',', $tipsArr) . '不能为空');
        }
        $model = new self;
        $model->setAttributes($designCenterProviderInfoForm->getAttributes());
        return $model->save();
    }

    /**
     * 编辑设计中心实体
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     * @throws ApiException
     * @author weifeng
     */
    public function updateEntity(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool
    {
        $mustArr = ['name' => '名称', 'quoted_price' => '报价', 'site' => '地点', 'recommended_reason' => '推荐理由', 'contact_way' => '联系方式'];
        $tipsArr = [];
        foreach ($designCenterProviderInfoForm->getAttributes() as $k => $v) {
            if (empty($v) && array_key_exists($k, $mustArr)) {
                $tipsArr[] = $mustArr[$k];
            }
        }
        if ($tipsArr) {
            throw new ApiException(implode(',', $tipsArr) . '不能为空');
        }
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
