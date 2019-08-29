<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\aggregate;

use app\common\utils\ArrayUtils;
use app\modules\v2\link\domain\dto\DeliveryVolumeDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeForm;
use app\modules\v2\link\domain\entity\DeliveryVolumeEntity as DeliveryVolumeAggregateRoot;
use app\modules\v2\link\service\StaticUrlDeliveryVolumeService;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class deliveryVolumeAggregate
 * @property-read  DeliveryVolumeAggregateRoot    $deliveryVolumeAggregateRoot
 * @property-read  staticUrlDeliveryVolumeService $staticUrlDeliveryVolumeService
 * @package app\modules\v2\link\domain\aggregate
 */
class DeliveryVolumeAggregate extends BaseObject
{
    /** @var DeliveryVolumeAggregateRoot */
    public $deliveryVolumeAggregateRoot;
    /** @var StaticUrlDeliveryVolumeService */
    public $staticUrlDeliveryVolumeService;


    public function __construct(
        DeliveryVolumeAggregateRoot $deliveryVolumeAggregateRoot,
        StaticUrlDeliveryVolumeService $staticUrlDeliveryVolumeService,
        $config = [])
    {
        $this->deliveryVolumeAggregateRoot    = $deliveryVolumeAggregateRoot;
        $this->staticUrlDeliveryVolumeService = $staticUrlDeliveryVolumeService;
        parent::__construct($config);
    }

    /**
     * 查看链接投放量
     * @param DeliveryVolumeDto $deliveryVolumeDto
     * @return array
     * @author zhuozhen
     */
    public function listDeliveryVolume(DeliveryVolumeDto $deliveryVolumeDto): array
    {
        //通过领域服务对聚合解耦，聚合对外唯一暴露聚合根
        $urlInfo = $this->staticUrlDeliveryVolumeService->staticListAggregate->staticListAggregateRoot::findOne((int)$deliveryVolumeDto->static_id);
        $list = $this->deliveryVolumeAggregateRoot->query((int)$deliveryVolumeDto->id);
        return ['url_info' => $urlInfo , 'list' => $list];
    }

    /**
     * 查看单个投放量
     * @param int $id
     * @return array
     * @author zhuozhen
     */
    public function viewDeliveryVolume(int $id) : array
    {
        return ArrayUtils::attributesAsMap($this->deliveryVolumeAggregateRoot::findOne(['id' => $id]));
    }


    /**
     * 创建投放量数据
     * @param DeliveryVolumeForm $deliveryVolumeForm
     * @throws Exception
     * @author zhuozhen
     */
    public function createDeliveryVolume(DeliveryVolumeForm $deliveryVolumeForm): void
    {
        $isExist = $this->deliveryVolumeAggregateRoot->exist($deliveryVolumeForm);
        if ($isExist === true){
            throw new Exception('该日期已存在记录，不能进行重复添加！');
        }
        $handledData = $this->staticUrlDeliveryVolumeService->processDeliveryVolumeData($deliveryVolumeForm);
        $result = $this->deliveryVolumeAggregateRoot->create($handledData);
        if ($result === false){
            throw new Exception('新增投放量失败');
        }
    }


    /**
     * 更新投放量数据
     * @param DeliveryVolumeForm $deliveryVolumeForm
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateDeliveryVolume(DeliveryVolumeForm $deliveryVolumeForm): bool
    {
        /** @var deliveryVolumeAggregateRoot $deliveryVolumeEntity */
        $deliveryVolumeEntity = $this->deliveryVolumeAggregateRoot::findOne(['id' => $deliveryVolumeForm->id]);
        if ($deliveryVolumeForm === null){
            throw new Exception('该投放量数据找不到');
        }
        $handledData = $this->staticUrlDeliveryVolumeService->processDeliveryVolumeData($deliveryVolumeForm);
        $deliveryVolumeEntity->setAttributes($handledData->getAttributes());
        return $deliveryVolumeEntity->save();

    }

    /**
     * 软删除
     * @param int $id
     * @return bool
     * @author zhuozhen
     */
    public function deleteDeliveryVolume(int $id): bool
    {
        /** @var deliveryVolumeAggregateRoot $deliveryVolumeEntity */
        $deliveryVolumeEntity = $this->deliveryVolumeAggregateRoot::findOne(['id' => $id]);
        $deliveryVolumeEntity->is_delete = 1;
        return $deliveryVolumeEntity->save();

    }


    /**
     * 生成投放数据
     * @param DeliveryVolumeDto $deliveryVolumeDto
     * @author zhuozhen
     */
    public function generateDeliveryData(DeliveryVolumeDto $deliveryVolumeDto): void
    {
        $deliveryVolumeGeneratedDto = $this->staticUrlDeliveryVolumeService->generateDeliveryData($deliveryVolumeDto);
        $this->deliveryVolumeAggregateRoot->processGeneratedData($deliveryVolumeGeneratedDto);
    }





}