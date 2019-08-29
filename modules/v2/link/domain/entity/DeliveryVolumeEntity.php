<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\DeliveryVolumeDo;
use app\modules\v2\link\domain\dto\DeliveryVolumeForm;
use app\modules\v2\link\domain\dto\DeliveryVolumeGeneratedDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeHandledDto;
use app\modules\v2\link\domain\dto\StaticUrlIntervalAnalyzeDto;
use yii\db\ActiveRecord;

class DeliveryVolumeEntity extends DeliveryVolumeDo
{


    /**
     * 获取链接指定时间内的投放量和转化成本
     * @param StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto
     * @return StaticClientEntity[]|StaticHitsEntity[]|StaticServiceConversionsEntity[]|StaticUrlGroupEntity[]|DeliveryVolumeEntity[]|StaticVisitEntity[]|array|ActiveRecord[]
     * @author zhuozhen
     */
    public function getUrlPutVolAndCvCost(StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto): array
    {
        $data = self::find()
            ->where(['is_delete' => 0])
            ->andWhere(['between', 'date', $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate()])
            ->andWhere(['static_url_id' => $staticUrlIntervalAnalyzeDto->id])
            ->asArray()
            ->all();

        $putVolumeList = [];
        foreach ($data as $item) {
            $putVolumeList[$item['date']] = $item;
        }
        return $putVolumeList;

    }

    /**
     * 查询统计链接投放量
     * @param $staticUrlId
     * @return array
     * @author zhuozhen
     */
    public function query(int $staticUrlId): array
    {
        return self::find()
            ->select(['id', 'put_volume', 'conversion_cost', 'date', 'creator', 'create_time'])
            ->where(['=', 'statis_url_id', $staticUrlId])
            ->andWhere(['is_delete' => 0])
            ->orderBy('date DESC')
            ->asArray()
            ->all();
    }

    /**
     * 新增投放量数据
     * @param DeliveryVolumeHandledDto $deliveryVolumeHandledDto
     * @return bool
     * @author zhuozhen
     */
    public function create(DeliveryVolumeHandledDto $deliveryVolumeHandledDto) : bool
    {
        $model = new self;
        $model->setAttributes($deliveryVolumeHandledDto->getAttributes());
        return $model->save();
    }




    /**
     * 检查投放量表对应链接此日期记录是否已存在
     * @param DeliveryVolumeForm $deliveryVolumeForm
     * @return bool
     * @author zhuozhen
     */
    public function exist(DeliveryVolumeForm $deliveryVolumeForm) : bool
    {
        return self::find()
            ->where(['date' => $deliveryVolumeForm->date])
            ->andWhere(['is_delete' => 0])
            ->andWhere(['statis_url_id' => $deliveryVolumeForm->static_id])
            ->exists();
    }




    /**
     * 处理生成投放数据
     * @param DeliveryVolumeGeneratedDto $deliveryVolumeGeneratedDto
     * @author zhuozhen
     */
    public function processGeneratedData(DeliveryVolumeGeneratedDto $deliveryVolumeGeneratedDto) : void
    {
        //TODO 处理生成的投放数据
    }


}