<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\aggregate;


use app\modules\v2\marketDept\domain\dto\TikTokCooperateDto;
use app\modules\v2\marketDept\domain\dto\TikTokCooperatePersonalInfoForm;
use app\modules\v2\marketDept\domain\repository\TikTokCooperateDoManager;
use app\modules\v2\marketDept\domain\entity\TikTokCooperateEntity as TikTokCooperateAggregateRoot;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class TikTokCooperateAggregate
 * @property TikTokCooperateAggregateRoot $tikTokCooperateAggregateRoot
 * @property TikTokCooperateDoManager     $tikTokCooperateDoManager
 * @property TikTokCooperateDto           $tikTokCooperateDto
 * @package app\modules\v2\marketDept\domain\aggregate
 */
class TikTokCooperateAggregate extends BaseObject
{

    public $tikTokCooperateAggregateRoot;
    /** @var TikTokCooperateDto */
    private $tikTokCooperateDto;
    /** @var TikTokCooperateDoManager */
    private $tikTokCooperateDoManager;

    public function __construct(
        TikTokCooperateAggregateRoot $tikTokCooperateAggregateRoot,
        TikTokCooperateDoManager $tikTokCooperateDoManager,
        TikTokCooperateDto $tikTokCooperateDto,
        $config = [])
    {
        $this->tikTokCooperateAggregateRoot = $tikTokCooperateAggregateRoot;
        $this->tikTokCooperateDto           = $tikTokCooperateDto;
        $this->tikTokCooperateDoManager     = $tikTokCooperateDoManager;
        parent::__construct($config);
    }

    /**
     * @param TikTokCooperateDto $TikTokCooperateDto
     * @return array
     * @author zhuozhen
     */
    public function listTikTokCooperate(TikTokCooperateDto $TikTokCooperateDto): array
    {
        return $this->tikTokCooperateDoManager->listDataProvider($TikTokCooperateDto)->getModels();

    }

    /**
     * @param TikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm
     * @return bool
     * @author zhuozhen
     */
    public function createTikTokCooperate(tikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm): bool
    {
        return $this->tikTokCooperateAggregateRoot->createEntity($tikTokCooperatePersonalInfoForm);
    }

    /**
     * @param TikTokCooperateDto $tikTokCooperateDto
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateTikTokCooperate(TikTokCooperateDto $tikTokCooperateDto) : bool
    {
        return $this->tikTokCooperateAggregateRoot->updateEntity($tikTokCooperateDto);
    }

    /**
     * @param int $tikTokCooperateId
     * @return int
     * @author zhuozhen
     */
    public function deleteTikTikCooperate(int $tikTokCooperateId) : int
    {
        return $this->tikTokCooperateAggregateRoot->deleteEntity($tikTokCooperateId);
    }
}