<?php


namespace app\modules\v2\link\domain\aggregate;


use app\modules\v2\link\domain\dto\StaticServiceDto;
use app\modules\v2\link\domain\entity\StaticServiceEntity;
use mdm\admin\BaseObject;

class StaticServiceAggregate extends BaseObject
{
    public $staticServiceEntity;

    public function __construct(
        StaticServiceEntity $staticServiceEntity,
        $config = [])
    {
        $this->staticServiceEntity = $staticServiceEntity;
        parent::__construct($config);
    }

    public function getService(StaticServiceDto $staticServiceDto)
    {

    }
}
