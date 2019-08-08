<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\aggregate;

use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use app\modules\v2\link\domain\entity\StaticHitsEntity;
use app\modules\v2\link\domain\entity\StaticServiceConversionsEntity;
use app\modules\v2\link\domain\entity\StaticUrlGroupEntity;
use app\modules\v2\link\domain\enum\Pattern;
use app\modules\v2\link\domain\repository\StaticUrlDoManager;
use app\modules\v2\link\domain\entity\StaticUrlEntity as StaticListAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;

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
        $list    = $this->staticUrlDoManager->listDataProvider($staticUrlDto, $this->staticUrlGroupEntity)->getModels();
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
                if ($ip['u_id'] === $item['id']) {
                    $list[$key]['ip'] = $ip['count'];
                }
            }
            foreach ($cvs as $cv) {
                if ($cv['u_id'] === $item['id']) {
                    $list[$key]['cv'] = $cv['count'];
                }
            }
        }

        $defaultGroupList = $this->staticUrlGroupEntity->getDefaultGroup();
        return [
            'list'             => $list,
            'defaultGroupList' => $defaultGroupList,
        ];
    }

    /**
     * 添加统计链接
     * @param StaticUrlForm $staticUrlForm
     * @return bool
     * @author zhuozhen
     */
    public function addStaticUrl(StaticUrlForm $staticUrlForm): bool
    {
        try {
            $this->staticListAggregateRoot->setAttributes(
                array_merge($staticUrlForm->getAttributes(),
                    ['ident' => $staticUrlForm->ident, 'm_id' => $staticUrlForm->mId]
                ));
            if ($this->staticListAggregateRoot->save() === false) {
                throw new Exception('创建统计链接失败');
            }
            $service = in_array($staticUrlForm->pattern, [Pattern::NOT_CIRCLE, Pattern::AUTO_CONVERSION], false) ? $staticUrlForm->service : trim(current($staticUrlForm->service_list));
            $this->staticServiceConversionsEntity->createEntity($staticUrlForm,$this->staticServiceConversionsEntity,$this->staticListAggregateRoot);
            $this->staticListAggregateRoot->updateEntity($this->staticListAggregateRoot, $service);
            return true;
        } catch (\Exception $exception) {
            Yii::info($exception->getMessage(), 'post_params');
            return false;
        }
    }


    /**
     * 更新统计链接
     * @param StaticUrlForm $staticUrlForm
     * @return bool
     * @author zhuozhen
     */
    public function updateStaticUrl(StaticUrlForm $staticUrlForm): bool
    {
        try {
            $staticUrl = $this->staticListAggregateRoot::findOne(['id' => $staticUrlForm->id]);
            if ($staticUrl === null) {
                throw new Exception('找不到该条统计链接');
            }
            $serviceConversions = $this->staticServiceConversionsEntity::findOne(['u_id' => $staticUrlForm->id]);
            $service            = in_array($staticUrlForm->pattern, [Pattern::NOT_CIRCLE, Pattern::AUTO_CONVERSION], false) ? $staticUrlForm->service : trim(current($staticUrlForm->service_list));
            if ($serviceConversions === null) {
                $this->staticServiceConversionsEntity->createEntity($staticUrlForm, $this->staticServiceConversionsEntity, $this->staticListAggregateRoot);
            } else {
                $this->staticServiceConversionsEntity->updateEntity($staticUrlForm, $serviceConversions, $this->staticListAggregateRoot);
            }
            $staticUrl->setAttributes(array_merge($staticUrlForm->getAttributes(),
                ['m_id' => $staticUrlForm->mId]));
            $this->staticListAggregateRoot->updateEntity($staticUrl, $service);
            return true;
        }catch (\Exception $exception){
            Yii::info($exception->getMessage(), 'post_params');
            return false;
        }
    }
}