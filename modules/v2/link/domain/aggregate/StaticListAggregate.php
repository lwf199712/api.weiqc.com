<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\aggregate;

use app\common\facade\DataSetCalculateFacade;
use app\common\facade\TimeFormatterFacade;
use app\common\infrastructure\enum\IntervalEnum;
use app\modules\v2\link\domain\dto\StaticUrlDeviceDto;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use app\modules\v2\link\domain\dto\StaticUrlIntervalAnalyzeDto;
use app\modules\v2\link\domain\dto\StaticUrlReportDto;
use app\modules\v2\link\domain\dto\StaticUrlVisitDetailDto;
use app\modules\v2\link\domain\entity\StaticClientEntity;
use app\modules\v2\link\domain\entity\StaticConversionEntity;
use app\modules\v2\link\domain\entity\StaticHitsEntity;
use app\modules\v2\link\domain\entity\StaticServiceConversionsEntity;
use app\modules\v2\link\domain\entity\StaticUrlGroupEntity;
use app\modules\v2\link\domain\entity\DeliveryVolumeEntity;
use app\modules\v2\link\domain\entity\StaticVisitEntity;
use app\modules\v2\link\domain\enum\Pattern;
use app\modules\v2\link\domain\repository\StaticUrlDoManager;
use app\modules\v2\link\domain\entity\StaticUrlEntity as StaticListAggregateRoot;
use app\modules\v2\link\service\StaticUrlDeliveryVolumeService;
use RuntimeException;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class StaticListAggregate
 * @property-read  StaticListAggregateRoot        $staticListAggregateRoot
 * @property-read  StaticUrlDeliveryVolumeService $staticUrlDeliveryVolumeService
 * @property-read  StaticUrlDoManager             $staticUrlDoManager
 * @property-read  StaticHitsEntity               $staticHitsEntity
 * @property-read  StaticClientEntity             $staticClientEntity
 * @property-read  StaticVisitEntity              $staticVisitEntity
 * @property-read  StaticConversionEntity         $staticConversionEntity
 * @property-read  StaticServiceConversionsEntity $staticServiceConversionsEntity
 * @property-read  StaticUrlGroupEntity           $staticUrlGroupEntity
 * @property-read  DeliveryVolumeEntity           $staticUrlPutVolumeEntity
 * @package app\modules\v2\link\domain\aggregate
 */
class StaticListAggregate extends BaseObject
{
    /** @var StaticUrlDeliveryVolumeService  */
    public $staticUrlDeliveryVolumeService;
    /** @var StaticListAggregateRoot */
    public $staticListAggregateRoot;
    /** @var StaticUrlDoManager */
    private $staticUrlDoManager;
    /** @var StaticHitsEntity */
    private $staticHitsEntity;
    /** @var StaticClientEntity */
    private $staticClientEntity;
    /** @var StaticVisitEntity */
    private $staticVisitEntity;
    /** @var StaticConversionEntity */
    private $staticConversionEntity;
    /** @var StaticServiceConversionsEntity */
    private $staticServiceConversionsEntity;
    /** @var StaticUrlGroupEntity */
    private $staticUrlGroupEntity;


    public function __construct(StaticUrlDeliveryVolumeService $staticUrlDeliveryVolumeService,
                                StaticUrlDoManager $staticUrlDoManager,
                                StaticListAggregateRoot $staticListAggregateRoot,
                                StaticHitsEntity $staticHitsEntity,
                                StaticClientEntity $staticClientEntity,
                                StaticVisitEntity $staticVisitEntity,
                                StaticConversionEntity $staticConversionEntity,
                                StaticServiceConversionsEntity $staticServiceConversionsEntity,
                                StaticUrlGroupEntity $staticUrlGroupEntity,
                                $config = [])
    {
        $this->staticUrlDeliveryVolumeService = $staticUrlDeliveryVolumeService;
        $this->staticListAggregateRoot        = $staticListAggregateRoot;
        $this->staticUrlDoManager             = $staticUrlDoManager;
        $this->staticHitsEntity               = $staticHitsEntity;
        $this->staticVisitEntity              = $staticVisitEntity;
        $this->staticClientEntity             = $staticClientEntity;
        $this->staticConversionEntity         = $staticConversionEntity;
        $this->staticServiceConversionsEntity = $staticServiceConversionsEntity;
        $this->staticUrlGroupEntity           = $staticUrlGroupEntity;
        parent::__construct($config);
    }


    /**
     * 获取单条数据
     * @param int $staticUrlId
     * @return array
     * @author zhuozhen
     */
    public function viewStaticUrl(int $staticUrlId): array
    {
        $row = $this->staticUrlDoManager->viewData($staticUrlId);
        return ['list' => $row];
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
            $this->staticServiceConversionsEntity->createEntity($staticUrlForm, $this->staticServiceConversionsEntity, $this->staticListAggregateRoot);
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
        } catch (\Exception $exception) {
            Yii::info($exception->getMessage(), 'post_params');
            return false;
        }
    }

    /**
     * 统计概况
     * @param StaticUrlReportDto $staticUrlReportDto
     * @return array
     * @author zhuozhen
     */
    public function reportStaticUrl(StaticUrlReportDto $staticUrlReportDto): array
    {
        $hits    = $this->staticHitsEntity->queryByStaticUrlForReport($staticUrlReportDto);
        $client  = $this->staticClientEntity->queryByStaticUrlForReport($staticUrlReportDto);
        $visit   = $this->staticVisitEntity->queryByStaticUrlForReport($staticUrlReportDto);
        $referer = $this->staticUrlDeliveryVolumeService->countPage($hits,'referer');
        $page    = $this->staticUrlDeliveryVolumeService->countPage($hits,'page');
        return ['hits' => count($hits), 'client' => count($client), 'visit' => count($visit), 'referer' => $referer, 'page' => $page];
    }


    /**
     * 时段分析
     * tips : 前端直接传时间戳，startAt,endAt
     * @param StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto
     * @return array
     * @author zhuozhen
     */
    public function intervalAnalyzeStaticUrl(StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto): array
    {
        $page = $visit = $section = [];

        //日期昨天，今天，或者时段在一天内，显示受访问页面URL，显示小时段分布
        //日期七天，三十天，本月，不显示受访问页面URL，显示日访问量分布
        if ($staticUrlIntervalAnalyzeDto->getEndDate() - $staticUrlIntervalAnalyzeDto->getBeginDate() < 24 * 3600) {
            $hits = $this->staticHitsEntity->queryByStaticUrlForAnalyze($staticUrlIntervalAnalyzeDto);
            $page = $this->staticUrlDeliveryVolumeService->countPage($hits,'page');
        }
        $putVolumeList = $this->staticUrlPutVolumeEntity->getUrlPutVolAndCvCost($staticUrlIntervalAnalyzeDto);

        if (isset($page)) {
            $conversionList = $this->staticConversionEntity->getCvCountByWeChat($staticUrlIntervalAnalyzeDto);                         //各公众号的转化数
            $cvCount        = array_sum(array_column($conversionList, 'conversion_count'));                                   //总转化数
            $putVolume      = $putVolumeList[$staticUrlIntervalAnalyzeDto->getBeginDate()]['put_volume'];                              //投放量
            //将url对应公众号的转化数存到已有的page数组中
            $page = $this->staticConversionEntity->fillConversionDataIntoPage($conversionList, $cvCount, $putVolume, $page);
        }

        //获取独立ip（ip），独立访客（uv），重复点击（pv）,转化数（cv）在特定时间范围内的所有创建时间记录
        $ip = $this->staticHitsEntity->queryByStaticUrlForAnalyze($staticUrlIntervalAnalyzeDto);
        $uv = $this->staticClientEntity->queryByStaticUrlForAnalyze($staticUrlIntervalAnalyzeDto);
        $pv = $this->staticVisitEntity->queryByStaticUrlForAnalyze($staticUrlIntervalAnalyzeDto);
        $cv = $this->staticConversionEntity->queryByStaticUrlForAnalyze($staticUrlIntervalAnalyzeDto);
        //TODO 统计数据集
        $statLinkDay['ip'] = DataSetCalculateFacade::count($ip, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::DAY, 1, 'createtime');
        $statLinkDay['uv'] = DataSetCalculateFacade::count($uv, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::DAY, 1, 'createtime');
        $statLinkDay['pv'] = DataSetCalculateFacade::count($pv, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::DAY, 1, 'createtime');
        $statLinkDay['cv'] = DataSetCalculateFacade::count($cv, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::DAY, 1, 'createtime');

        $avg = ['day' => 0, 'hour' => 0];
        foreach ($statLinkDay['ip'] as $timeStamp => $val) {
            $day               = date('m-d', $timeStamp);
            $visit['ip'][$day] = $val['createtime'];
            $visit['uv'][$day] = $statLinkDay['uv'][$timeStamp]['createtime'];
            $visit['pv'][$day] = $statLinkDay['pv'][$timeStamp]['createtime'];
            $visit['cv'][$day] = $statLinkDay['cv'][$timeStamp]['createtime'];
            //转化率
            $cvRate                = round($statLinkDay['cv'][$timeStamp]['createtime'] / $val['createtime'], 2);
            $visit['cvRate'][$day] = $cvRate * 100 . '%';
            //转化成本
            $visit['turnoverCost'][$day] = isset($putVolumeList[$timeStamp]) ? $putVolumeList[$timeStamp]['conversion_cost'] : '';
            //投放量
            $visit['putVolume'][$day] = isset($putVolumeList[$timeStamp]) ? $putVolumeList[$timeStamp]['put_volume'] : '';
            //平均点击数=重复点击/独立访客
            $visit['avg'][$day] = sprintf('%.2f', $visit['pv'][$day] / $visit['uv'][$day]);
            $avg['day']         += $visit['avg'][$day];
        }

        $statLinkHour['ip'] = DataSetCalculateFacade::count($ip, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::HOUR, 1, 'createtime');
        $statLinkHour['pv'] = DataSetCalculateFacade::count($pv, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::HOUR, 1, 'createtime');
        $statLinkHour['uv'] = DataSetCalculateFacade::count($uv, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::HOUR, 1, 'createtime');
        $statLinkHour['cv'] = DataSetCalculateFacade::count($cv, $staticUrlIntervalAnalyzeDto->getBeginDate(), $staticUrlIntervalAnalyzeDto->getEndDate(), IntervalEnum::HOUR, 1, 'createtime');

        foreach ($statLinkHour['ip'] as $timeStamp => $val) {
            $hour                          = date('H', $timeStamp);
            $sectionScope                  = $hour . ':00 - ' . gmdate('H:i', TimeFormatterFacade::toSecond($hour . ':00') + 3600);
            $section['wxh'][$sectionScope] = $statLinkHour['cv'][$timeStamp]['wxh'];
            $section['ip'][$sectionScope]  += $val['createtime'];
            $section['uv'][$sectionScope]  += $statLinkHour['uv'][$timeStamp]['createtime'];
            $section['pv'][$sectionScope]  += $statLinkHour['pv'][$timeStamp]['createtime'];
            $section['cv'][$sectionScope]  += $statLinkHour['cv'][$timeStamp]['createtime'];
        }

        foreach ($section['ip'] as $timeStamp => $val) {
            //人均点击次数=重复点击/独立访客
            $section['avg'][$timeStamp] = sprintf('%.2f', $section['pv'][$timeStamp] / $section['uv'][$timeStamp]);
            $avg['hour']                += $section['avg'][$timeStamp];
        }


        return [
            'page'    => $page,
            'avg'     => $avg,
            'visit'   => $visit,
            'section' => $section,
        ];
    }

    /**
     * 访问明细
     * @param StaticUrlVisitDetailDto $staticUrlVisitDetailDto
     * @return array
     * @throws Exception
     * @author zhuozhen
     */
    public function visitDetailStaticUrl(StaticUrlVisitDetailDto $staticUrlVisitDetailDto): array
    {
        if ($this->staticListAggregateRoot->checkExist((int)$staticUrlVisitDetailDto->id) === false) {
            throw new Exception('找不到统计链接，请核实');
        }
        switch ($staticUrlVisitDetailDto->type) {
            case 'ip':
                $data = $this->staticHitsEntity->queryVisitDetail($staticUrlVisitDetailDto);
                break;
            case 'uv':
                $data = $this->staticClientEntity->queryVisitDetail($staticUrlVisitDetailDto);
                break;
            case 'pv':
                $data = $this->staticVisitEntity->queryVisitDetail($staticUrlVisitDetailDto);
                break;
            case 'cv':
                $data = $this->staticConversionEntity->queryVisitDetail($staticUrlVisitDetailDto);
                break;
        }
        return $data ?? [];

    }

    /**
     * 流量设备
     * @param StaticUrlDeviceDto $staticUrlDeviceDto
     * @author zhuozhen
     */
    public function DeviceStaticUrl(StaticUrlDeviceDto $staticUrlDeviceDto)
    {
        $data = $this->staticClientEntity->queryDevice($staticUrlDeviceDto);

    }

    /**
     * 页面监控
     * @author zhuozhen
     */
    public function pageMonitorStaticUrl()
    {
    }


    /**
     * 获取公众号的的转化数及消耗（投放量）
     * @param DeliveryVolumeEntity $deliveryVolumeEntity
     * @deprecated  需要重写，返回上一步
     * @author zhuozhen
     */
    public function getServiceCvAndConsume(DeliveryVolumeEntity $deliveryVolumeEntity)
    {
        $beginTime = (int)$deliveryVolumeEntity->date;
        $endTime = strtotime('+1 day', $beginTime) - 1;
        $hits = $this->staticHitsEntity->queryByStaticUrlForDeliveryVolume($deliveryVolumeEntity->id ,$beginTime, $endTime);
        $hitsPage = $this->staticUrlDeliveryVolumeService->countPage($hits,'page');
        $cvs = $this->staticConversionEntity->queryByStaticUrlForDelivery($deliveryVolumeEntity->id ,$beginTime, $endTime);
        $cvsPage =  $this->staticUrlDeliveryVolumeService->countPage($cvs,'wxh');

        //总转换数
        $totalCvCount = array_sum($cvsPage);
        //投放量
        $putVolume = $deliveryVolumeEntity->put_volume;

        //TODO...

    }
}