<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\aggregate;

use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\entity\StaticHitsEntity;
use app\modules\v2\link\domain\entity\StaticServiceConversionsEntity;
use app\modules\v2\link\domain\entity\StaticUrlGroupEntity;
use app\modules\v2\link\domain\repository\StaticUrlDoManager;
use app\modules\v2\link\domain\entity\StaticUrlEntity as StaticListAggregateRoot;
use yii\base\BaseObject;

/**
 * Class StaticListAggregate
 * @property-read  StaticListAggregateRoot        $staticListAggregateRoot
 * @property-read  StaticUrlDoManager             $staticUrlDoManager
 * @property-read  staticHitsEntity               $staticHitsEntity
 * @property-read  staticServiceConversionsEntity $staticServiceConversionsEntity
 * @property-read  staticUrlGroupEntity           $staticUrlGroupEntity
 * @package app\modules\v2\link\domain\aggregate
 */
class StaticListAggregate extends BaseObject
{
    /** @var StaticListAggregateRoot */
    public $staticListAggregateRoot;
    /** @var StaticUrlDoManager */
    private $staticUrlDoManager;
    /** @var StaticHitsEntity */
    private $staticHitsEntity;
    /** @var StaticServiceConversionsEntity */
    private $staticServiceConversionsEntity;
    /** @var StaticUrlGroupEntity */
    private $staticUrlGroupEntity;


    public function __construct(StaticUrlDoManager $staticUrlDoManager,
                                StaticListAggregateRoot $staticListAggregateRoot,
                                StaticHitsEntity $staticHitsEntity,
                                StaticServiceConversionsEntity $staticServiceConversionsEntity,
                                StaticUrlGroupEntity $staticUrlGroupEntity,
                                $config = [])
    {
        $this->staticListAggregateRoot        = $staticListAggregateRoot;
        $this->staticUrlDoManager             = $staticUrlDoManager;
        $this->staticHitsEntity               = $staticHitsEntity;
        $this->staticServiceConversionsEntity = $staticServiceConversionsEntity;
        $this->staticUrlGroupEntity           = $staticUrlGroupEntity;
        parent::__construct($config);
    }


    /**
     * 获取列表数据
     * @param StaticUrlDto $staticUrlDto
     * @return mixed
     */
    public function listStaticUrl(StaticUrlDto $staticUrlDto): array
    {
        $list    = $this->staticUrlDoManager->listDataProvider($staticUrlDto, $this->staticListAggregateRoot)->getModels();
        $uIdList = array_column($list, 'id');
        $ips     = $this->staticHitsEntity->getStaticHitsData($uIdList);                             //独立IP
        $cvs     = $this->staticServiceConversionsEntity->getServiceConversionData($uIdList);        //转换数


        foreach ($list as $key => $item) {
            $list[$key]['groupname'] = empty($item['desc']) ? $item['groupname'] : $item['groupname'] . '-' . $item['desc'];
            if (($offset = stripos($item['url'], 'wxh=')) !== false) {
                $list[$key]['currentDept'] = substr($item['url'], $offset + 4);
            } else {
                $list[$key]['currentDept'] = '';
            }
            foreach ($ips as $ip) {
                if ($ip['u_id'] === $item['staticUrl.id']) {
                    $list[$key]['ip'] = $ip['count'];
                }
            }
            foreach ($cvs as $cv) {
                if ($cv['u_id'] === $item['staticUrl.id']) {
                    $list[$key]['ip'] = $cv['count'];
                }
            }
        }

        $defaultGroupList = $this->staticUrlGroupEntity->getDefaultGroup();
        return [
            'list'             => $list,
            'defaultGroupList' => $defaultGroupList,
        ];
    }
}