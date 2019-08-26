<?php declare(strict_types=1);

namespace app\modules\v2\link\service\impl;


use app\modules\v2\link\domain\aggregate\DeliveryVolumeAggregate;
use app\modules\v2\link\domain\aggregate\StaticListAggregate;
use app\modules\v2\link\domain\dto\DeliveryVolumeDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeForm;
use app\modules\v2\link\domain\dto\DeliveryVolumeGeneratedDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeHandledDto;
use app\modules\v2\link\domain\entity\DeliveryVolumeEntity;
use app\modules\v2\link\service\StaticUrlDeliveryVolumeService;
use yii\base\BaseObject;
use yii\db\Exception;

class StaticUrlDeliveryVolumeImpl extends BaseObject implements StaticUrlDeliveryVolumeService
{
    /** @var StaticListAggregate */
    public $staticListAggregate;
    /** @var DeliveryVolumeAggregate */
    public $deliveryVolumeAggregate;
    /** @var DeliveryVolumeHandledDto */
    public $deliveryVolumeHandledDto;

    public function __construct(
        StaticListAggregate $staticListAggregate,
        DeliveryVolumeAggregate $deliveryVolumeAggregate,
        DeliveryVolumeHandledDto $deliveryVolumeHandledDto,
        $config = [])
    {
        $this->staticListAggregate     = $staticListAggregate;
        $this->deliveryVolumeAggregate = $deliveryVolumeAggregate;
        $this->deliveryVolumeHandledDto = $deliveryVolumeHandledDto;
        parent::__construct($config);
    }


    /**
     * 处理提交的投放量数据
     * @param DeliveryVolumeForm $deliveryVolumeForm
     * @return DeliveryVolumeHandledDto
     * @author zhuozhen
     */
    public function processDeliveryVolumeData(DeliveryVolumeForm $deliveryVolumeForm): DeliveryVolumeHandledDto
    {
        $beginTime = $deliveryVolumeForm->date;
        $endTime = strtotime('+1 day',$beginTime) - 1 ;
        //获取转化成本
        $cvCount =  $this->staticListAggregate->staticConversionEntity->getCvCountById($beginTime,$endTime,$deliveryVolumeForm->static_id);
        $conversionCost = empty($cvCount) ? $_POST['put_volume'] : round($_POST['put_volume'] / $cvCount, 2);
        $this->deliveryVolumeHandledDto->setAttributes(array_merge($deliveryVolumeForm->getAttributes(),[
            'conversion_cost' => $conversionCost,
        ]));
        return $this->deliveryVolumeHandledDto;
    }

    /**
     * 生成投放数据
     * @param DeliveryVolumeDto $deliveryVolumeDto
     * @return DeliveryVolumeGeneratedDto
     * @throws Exception
     * @author zhuozhen
     */
    public function generateDeliveryData(DeliveryVolumeDto $deliveryVolumeDto) : DeliveryVolumeGeneratedDto
    {
        //TODO 采用领域事件来解耦生成投放数据过程, tip : 长时处理过程
        /** @var DeliveryVolumeEntity $deliveryVolumeEntity */
        $deliveryVolumeEntity = $this->deliveryVolumeAggregate->deliveryVolumeAggregateRoot::findOne(['id' => $deliveryVolumeDto->id]);
        if ($deliveryVolumeEntity === null || (int)$deliveryVolumeEntity->is_delete === 1){
            throw new Exception('找不到投放量数据或该数据被删除');
        }
        //deprecated
        $this->staticListAggregate->getServiceCvAndConsume($deliveryVolumeEntity);

    }


    /**
     * 统计受访页面URL
     * @param array  $hits
     * @param string $groupBy
     * @return array
     * @author zhuozhen
     */
    public function countPage(array $hits,string $groupBy): array
    {
        $list = [];
        array_map(static function ($hit) use ($groupBy, $list) {
            if ($hit[$groupBy] !== '') {
                $list[$groupBy] = isset($list[$groupBy]) ? $list[$groupBy]++ : $list[$groupBy] = 0;
            }
        }, $hits);
        arsort($list);
        return $list;
    }
}