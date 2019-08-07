<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\aggregate;

use app\modules\v1\userAction\domain\entity\StaticServiceConversionEntity;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use app\modules\v2\link\domain\entity\StaticHitsEntity;
use app\modules\v2\link\domain\entity\StaticServiceConversionsEntity;
use app\modules\v2\link\domain\entity\StaticUrlGroupEntity;
use app\modules\v2\link\domain\enum\Pattern;
use app\modules\v2\link\domain\repository\StaticUrlDoManager;
use app\modules\v2\link\domain\entity\StaticUrlEntity as StaticListAggregateRoot;
use Throwable;
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
 * @property-read  StaticServiceConversionEntity  $staticServiceConversionEntity
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
    /** @var StaticServiceConversionEntity */
    private $staticServiceConversionEntity;


    public function __construct(StaticUrlDoManager $staticUrlDoManager,
                                StaticListAggregateRoot $staticListAggregateRoot,
                                StaticHitsEntity $staticHitsEntity,
                                StaticServiceConversionsEntity $staticServiceConversionsEntity,
                                StaticUrlGroupEntity $staticUrlGroupEntity,
                                StaticServiceConversionEntity $staticServiceConversionEntity,
                                $config = [])
    {
        $this->staticListAggregateRoot        = $staticListAggregateRoot;
        $this->staticUrlDoManager             = $staticUrlDoManager;
        $this->staticHitsEntity               = $staticHitsEntity;
        $this->staticServiceConversionsEntity = $staticServiceConversionsEntity;
        $this->staticUrlGroupEntity           = $staticUrlGroupEntity;
        $this->staticServiceConversionEntity  = $staticServiceConversionEntity;
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
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->staticListAggregateRoot->setAttributes(
                array_merge($staticUrlForm->getAttributes(),
                    ['ident' => $staticUrlForm->ident, 'm_id' => $staticUrlForm->mId]
                ));
            if ($this->staticListAggregateRoot->save() === false) {
                throw new Exception('创建统计链接失败');
            }
            $service = in_array($staticUrlForm->pattern, [Pattern::NOT_CIRCLE, Pattern::AUTO_CONVERSION], false) ? $staticUrlForm->service : trim(current($staticUrlForm->service_list));
            $this->staticServiceConversionEntity->setAttributes(
                array_merge($staticUrlForm->getAttributes(),
                    ['u_id'             => $this->staticListAggregateRoot->id,
                     'service'          => $service,
                     'original_service' => $service,
                     'service_list'     => implode(',', $staticUrlForm->service_list),
                     'conversions_list' => implode(',', $staticUrlForm->conversions_list)])
            );

            if ($this->staticServiceConversionEntity->save() === false) {
                throw new Exception('创建统计链接转化数表失败');
            }
            $this->staticListAggregateRoot->updateUrl($this->staticListAggregateRoot, $service);
            $transaction->commit();
            return true;
        } catch (Throwable $throwable) {
            Yii::info($throwable->getMessage(), 'post_params');
            $transaction->rollBack();
            return false;
        }

    }


}