<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticServiceConversionsDo;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use app\modules\v2\link\domain\entity\StaticUrlEntity as StaticListAggregateRoot;
use app\modules\v2\link\domain\enum\Pattern;
use yii\db\Exception;

class StaticServiceConversionsEntity extends StaticServiceConversionsDo
{
    /**
     * 获取转换数
     * @param array $uIdList
     * @return array
     * @author zhuozhen
     */
    public function getServiceConversionData(array $uIdList): array
    {
        return self::find()->select(['u_id', 'count(id) as count'])->where(['in', 'u_id', $uIdList])->groupBy('u_id')->all();
    }


    /**
     * 创建统计链接转换数实体
     * @param StaticUrlForm                  $staticUrlForm
     * @param StaticServiceConversionsEntity $staticServiceConversionsEntity
     * @param StaticListAggregateRoot        $staticListAggregateRoot
     * @throws Exception
     * @author zhuozhen
     */
    public function createEntity(StaticUrlForm $staticUrlForm, StaticServiceConversionsEntity $staticServiceConversionsEntity, StaticListAggregateRoot $staticListAggregateRoot): void
    {
        $service = in_array($staticUrlForm->pattern, [Pattern::NOT_CIRCLE, Pattern::AUTO_CONVERSION], false) ? $staticUrlForm->service : trim(current($staticUrlForm->service_list));
        $staticServiceConversionsEntity->setAttributes(
            array_merge($staticUrlForm->getAttributes(),
                ['u_id'             => $staticListAggregateRoot->id,
                 'service'          => $service,
                 'original_service' => $service,
                 'service_list'     => implode(',', $staticUrlForm->service_list),
                 'conversions_list' => implode(',', $staticUrlForm->conversions_list)])
        );

        if ($staticServiceConversionsEntity->save() === false) {
            throw new Exception('创建统计链接转化数表失败');
        }
    }

    /**
     * 更新统计链接转换数实体
     * @param StaticUrlForm                  $staticUrlForm
     * @param StaticServiceConversionsEntity $staticServiceConversionsEntity
     * @param StaticUrlEntity                $staticListAggregateRoot
     * @throws Exception
     * @author zhuozhen
     */
    public function updateEntity(StaticUrlForm $staticUrlForm, StaticServiceConversionsEntity $staticServiceConversionsEntity, StaticListAggregateRoot $staticListAggregateRoot): void
    {
        $service = in_array($staticUrlForm->pattern, [Pattern::NOT_CIRCLE, Pattern::AUTO_CONVERSION], false) ? $staticUrlForm->service : trim(current($staticUrlForm->service_list));
        $staticServiceConversionsEntity->setAttributes(
            array_merge($staticUrlForm->getAttributes(),
                ['u_id'             => $staticListAggregateRoot->id,
                 'service'          => $service,
                 'original_service' => $service,
                 'service_list'     => implode(',', $staticUrlForm->service_list),
                 'conversions_list' => implode(',', $staticUrlForm->conversions_list)])
        );

        if ($staticServiceConversionsEntity->save() === false) {
            throw new Exception('更新统计链接转化数表失败');
        }
    }
}