<?php
declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\aggregate;


use app\common\facade\ExcelFacade;
use app\modules\v2\advertDept\domain\dto\VideoStatisticsDto;
use app\modules\v2\advertDept\domain\entity\MktadEntity;
use app\modules\v2\advertDept\domain\entity\MktadRebateEntity;
use app\modules\v2\advertDept\domain\entity\MktadServiceEntity;
use app\modules\v2\advertDept\domain\entity\TestMktadEntity;
use Exception;
use yii\base\BaseObject;
use yii\base\Model;
use function GuzzleHttp\Psr7\str;

class VideoStatisticsAggregate extends BaseObject
{
    /** @var MktadEntity */
    private $mktadEntity;

    /** @var MktadRebateEntity */
    private $mktadRebateEntity;

    /** @var TestMktadEntity */
    private $testMktadEntity;

    /** @var MktadServiceEntity */
    private $mktadServiceEntity;

    public function __construct(MktadEntity $mktadEntity,
                                MktadRebateEntity $mktadRebateEntity,
                                TestMktadEntity $testMktadEntity,
                                MktadServiceEntity $mktadServiceEntity,
                                $config = [])
    {
        $this->mktadEntity = $mktadEntity;
        $this->mktadRebateEntity = $mktadRebateEntity;
        $this->testMktadEntity = $testMktadEntity;
        $this->mktadServiceEntity = $mktadServiceEntity;
        parent::__construct($config);
    }

    /**
     * 获取视频统计首页数据
     * @param VideoStatisticsDto $videoStatisticsDto
     * @param bool               $isExport
     * @return array
     * @author dengkai
     * @date   2019/10/6
     */
    public function getVideoStatisticsHomePageData(VideoStatisticsDto $videoStatisticsDto, bool $isExport = false): array
    {
        //通过视频id进行分组，以准确获取分页数据
        $queryTime = $this->getQueryDate($videoStatisticsDto);
        $fieldOne = 'm.id,m.s_id,md.v_id,v.video_name';
        $query = $this->mktadEntity->selectVideoStatisticsPutData($videoStatisticsDto, $fieldOne, $queryTime, 'md.v_id');
        $providerObj = $this->mktadEntity->getActiveDataProvider($query->asArray(), $videoStatisticsDto, ['md.id' => SORT_DESC]);
        //获取分页总记录数
        $count = $providerObj->getTotalCount();
        $res = $providerObj->getModels();
        //查询所有服务号
        $service = $this->mktadServiceEntity->selectAllService();
        $serviceList = array_column($service, 'service', 'id');

        if (empty($count)) {
            return ['list' => [], 'serviceList' => $serviceList, 'page' => $videoStatisticsDto->page, 'perPage' => $videoStatisticsDto->perPage, 'count' => 0];
        }

        //获取当前页 大投/测试编号、跟进人、渠道投放量详情 三列的数据
        $videoNameArr = array_column($res, 'video_name', 'v_id');
        $videoIdArr = array_column($res, 'v_id');

        $putDataField = 'm.id,m.u_id,m.s_id,u.username,md.number,md.consume,md.r_id,md.v_id,md.is_test,md.dc_conversion_rate,md.leakage_rate';
        $putDataQuery = $this->mktadEntity->selectPutData($videoStatisticsDto, $putDataField, $videoIdArr, $queryTime);

        if ($isExport) {
            $putDataList = $putDataQuery->orderBy(['md.id' => SORT_DESC])->asArray()->all();
        } else {
            $putDataList = $this->mktadEntity->getActiveDataProvider($putDataQuery->asArray(), $videoStatisticsDto, ['md.id' => SORT_DESC])->getModels();
        }

        //获取返点名称及返点值
        $rebateIdArr = array_unique(array_column($putDataList, 'r_id'));
        $rebateList = $this->mktadRebateEntity->selectRebateDataById($rebateIdArr, 'id,channel_name,rebate');

        //处理首页-渠道投放量详情的数据
        $homePageList = $this->setVideoStatisticsPutData($putDataList, $rebateList, $videoNameArr);

        $testNumber = $bigNumber = [];
        foreach ($putDataList as $key => $record) {
            if (strpos($record['number'], 'D') === false) {
                $testNumber[] = $record['number'];
            } else {
                $bigNumber[] = $record['number'];
            }
        }

        $testList = $bigList = [];
        $fieldArr = ['m.id', 'm.test_number', 'md.fans_num', 'md.consume', 'md.r_id', 'md.turnover', 'md.conversion_rate', 'md.under_seventeen_count', 'md.silence_count', 'md.cancel_attention_count'];
        //查询测试编号数据
        if (!empty($testNumber)) {
            $testList = $this->testMktadEntity->selectTestMktadData($fieldArr, array_unique($testNumber), $queryTime, true);
        }
        //查询大投编号关联的数据
        if (!empty($bigNumber)) {
            $fieldArr[] = 'b.delivery_number';
            $bigList = $this->testMktadEntity->selectTestMktadData($fieldArr, array_unique($bigNumber), $queryTime, false);
        }

        $testMktadRebate = array_merge(array_column($testList, 'r_id'), array_column($bigList, 'r_id'));
        $testMktadRebateList = $this->mktadRebateEntity->selectRebateDataById(array_unique($testMktadRebate), 'id,rebate');

        //处理首页测试字段数据并汇总
        $homePageList = $this->setVideoStatisticsTestMktadData($homePageList, $putDataList, $testList, $bigList, $testMktadRebateList);

        $data = ['list' => $homePageList, 'serviceList' => $serviceList, 'page' => $videoStatisticsDto->page, 'perPage' => $videoStatisticsDto->perPage, 'count' => $count];

        return $data;
    }


    /**
     * 获取视频测试详情首页数据
     * @param VideoStatisticsDto $videoStatisticsDto
     * @param bool               $isExport
     * @return array
     * @author dengkai
     * @date   2019/9/28
     */
    public function getVideoTestDetailHomePageData(VideoStatisticsDto $videoStatisticsDto, bool $isExport = false): array
    {
        //通过测试号进行分组，以准确获取分页数据
        $queryTime = $this->getQueryDate($videoStatisticsDto);
        $query = $this->mktadEntity->selectPutData($videoStatisticsDto, 'm.id,m.u_id,m.s_id,md.number,u.username', [$videoStatisticsDto->videoId], $queryTime, 'md.number');
        $providerObj = $this->mktadEntity->getActiveDataProvider($query->asArray(), $videoStatisticsDto, ['md.id' => SORT_DESC]);
        //获取分页总记录数
        $count = $providerObj->getTotalCount();
        //        $res = $providerObj->getModels();
        //查询所有服务号
        $service = $this->mktadServiceEntity->selectAllService();
        $serviceList = array_column($service, 'service', 'id');

        if (empty($count)) {
            return ['list' => [], 'serviceList' => $serviceList, 'page' => $videoStatisticsDto->page, 'perPage' => $videoStatisticsDto->perPage, 'count' => 0];
        }

        //获取当前页 大投/测试编号、跟进人、渠道投放量详情 三列的数据
        //        $numberArr = array_column($res, 'number');
        $putDataField = 'm.id,m.u_id,m.s_id,md.number,u.username,md.consume,md.r_id,md.is_test';
        $putDataQuery = $this->mktadEntity->selectPutData($videoStatisticsDto, $putDataField, [$videoStatisticsDto->videoId], $queryTime);
        //        $putDataQuery->andWhere(['md.number' => $numberArr])->asArray();

        if ($isExport) {
            $putDataList = $putDataQuery->orderBy(['md.id' => SORT_DESC])->asArray()->all();
        } else {
            $putDataList = $this->mktadEntity->getActiveDataProvider($putDataQuery->asArray(), $videoStatisticsDto, ['md.id' => SORT_DESC])->getModels();
        }

        //获取返点名称及返点值
        $rebateIdArr = array_unique(array_column($putDataList, 'r_id'));
        $rebateList = $this->mktadRebateEntity->selectRebateDataById($rebateIdArr, 'id,channel_name,rebate');

        //处理首页-大投/测试编号、跟进人、渠道投放量详情的数据
        $homePageList = $this->setPutData($putDataList, $rebateList);

        $testNumber = $bigNumber = [];
        foreach ($putDataList as $key => $record) {
            if (strpos($record['number'], 'D') === false) {
                $testNumber[] = $record['number'];
            } else {
                $bigNumber[] = $record['number'];
            }
        }

        $testList = $bigList = [];
        $fieldArr = ['m.id', 'm.test_number', 'md.fans_num', 'md.consume', 'md.r_id', 'md.turnover', 'md.conversion_rate', 'md.under_seventeen_count', 'md.silence_count', 'md.cancel_attention_count'];
        //查询测试编号数据
        if (!empty($testNumber)) {
            $testList = $this->testMktadEntity->selectTestMktadData($fieldArr, array_unique($testNumber), $queryTime, true);
        }
        //查询大投编号关联的数据
        if (!empty($bigNumber)) {
            $fieldArr[] = 'b.delivery_number';
            $bigList = $this->testMktadEntity->selectTestMktadData($fieldArr, array_unique($bigNumber), $queryTime, false);
        }

        $testMktadRebate = array_merge(array_column($testList, 'r_id'), array_column($bigList, 'r_id'));
        $testMktadRebateList = $this->mktadRebateEntity->selectRebateDataById(array_unique($testMktadRebate), 'id,rebate');

        //处理首页测试字段数据
        $homePageList = $this->setTestMktadData($homePageList, $testList, $bigList, $testMktadRebateList);

        $data = ['list' => $homePageList, 'serviceList' => $serviceList, 'page' => $videoStatisticsDto->page, 'perPage' => $videoStatisticsDto->perPage, 'count' => $count];

        return $data;
    }

    /**
     * 处理首页-大投/测试编号、跟进人、渠道投放量详情的数据
     * @param array $putDataList
     * @param array $rebateList
     * @return array
     * @author dengkai
     * @date   2019/9/28
     */
    private function setPutData(array $putDataList, array $rebateList): array
    {
        $list = $detail = [];
        foreach ($putDataList as $key => $record) {
            //测试/大投编号
            $list[$record['number']]['number'] = $record['number'];
            //跟进人
            $list[$record['number']]['follower'] = $record['username'];

            //实际成本=消耗/返点
            $actualCost = round($record['consume'] / $rebateList[$record['r_id']]['rebate'], 2);

            /** 统计渠道投放量详情（同一个编号可能存在相同的返点的数据，相同的返点则数据进行相加） */
            //返点名称
            $rebateName = $detail[$record['number']]['channelPutDetail']
            [$record['r_id']]['rebateName'] = $rebateList[$record['r_id']]['channel_name'];
            //消耗
            $consumeTotal = $detail[$record['number']]['channelPutDetail']
            [$record['r_id']]['consume'] = ($detail[$record['number']]['channelPutDetail']
                    [$record['r_id']]['consume'] ?? 0) + $record['consume'];
            //实际成本
            $actualCostTotal = $detail[$record['number']]['channelPutDetail']
            [$record['r_id']]['actualCost'] = ($detail[$record['number']]['channelPutDetail']
                    [$record['r_id']]['actualCost'] ?? 0) + $actualCost;

            $list[$record['number']]['channelPutDetail'][$record['r_id']] = $rebateName . '--' . '消耗：' . $consumeTotal . '--' . '实际成本：' . $actualCostTotal;
        }

        return $list;
    }

    /**
     * 将首页测试字段的数据信息存储到主数组中
     * @param array $homePageList
     * @param array $testList
     * @param array $bigList
     * @param array $rebateList
     * @return array
     * @author dengkai
     * @date   2019/9/29
     */
    private function setTestMktadData(array $homePageList, array $testList, array $bigList, array $rebateList): array
    {

        [$lists, $totalData] = $this->getNumberDataAndTotalData($testList, $bigList, $rebateList);

        //首页后几个字段key
        $keyArr = ['testFansNum', 'actualCost', 'fansPrice', 'conversionRate', 'underSeventeenProportion', 'silenceRate', 'cancelAttentionRate', 'roi', 'realization'];
        foreach ($homePageList as $number => $record) {
            if (isset($lists[$number])) {
                foreach ($keyArr as $key) {
                    $homePageList[$number][$key] = (string)$lists[$number][$key];
                }
            } else {
                foreach ($keyArr as $key) {
                    $homePageList[$number][$key] = 0;
                }
            }
        }

        $homePageList = array_values($homePageList);
        $listCount = count($homePageList);
        /** 底部数据汇总 */
        $homePageList[$listCount]['number'] = '汇总';
        $homePageList[$listCount]['follower'] = '/';
        $homePageList[$listCount]['channelPutDetail'] = '/';
        $homePageList[$listCount]['testFansNum'] = (string)$totalData['totalTestFansNum'];
        $homePageList[$listCount]['actualCost'] = (string)$totalData['totalActualCost'];
        $homePageList[$listCount]['fansPrice'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalActualCost'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['conversionRate'] = (string)round(array_sum(array_column($homePageList, 'conversionRate')) / $listCount, 2);
        $homePageList[$listCount]['underSeventeenProportion'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalUnderSeventeenCount'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['silenceRate'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalSilenceCount'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['cancelAttentionRate'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalCancelAttentionCount'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['roi'] = (string)(empty($totalData['totalActualCost']) ? 0 : round($totalData['totalTurnover'] / $totalData['totalActualCost'], 2));
        $homePageList[$listCount]['realization'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalTurnover'] / $totalData['totalTestFansNum'], 2));

        return $homePageList;
    }


    /**
     * 处理首页列表需要展示的后部分字段测试数据
     * @param array  $testMktadList
     * @param string $oneDimensionalKey
     * @param array  $rebateList
     * @return array
     * @author dengkai
     * @date   2019/9/29
     */
    private function dealWithTestMktadData(array $testMktadList, string $oneDimensionalKey, array $rebateList): array
    {
        $list = [];
        $numberData = [];
        $totalData = [];

        foreach ($testMktadList as $record) {
            //实际成本=消耗/返点
            $actualCost = round($record['consume'] / $rebateList[$record['r_id']]['rebate'], 2);

            /** 所有编号的数据汇总 */
            //总实际成本
            $totalData['totalActualCost'] = ($totalData['totalActualCost'] ?? 0) + $actualCost;
            //总进粉数
            $totalData['totalTestFansNum'] = ($totalData['totalTestFansNum'] ?? 0) + $record['fans_num'];
            //总17岁以下数
            $totalData['totalUnderSeventeenCount'] = ($totalData['totalUnderSeventeenCount'] ?? 0) + $record['under_seventeen_count'];
            //总沉默数
            $totalData['totalSilenceCount'] = ($totalData['totalSilenceCount'] ?? 0) + $record['silence_count'];
            //总取关数
            $totalData['totalCancelAttentionCount'] = ($totalData['totalCancelAttentionCount'] ?? 0) + $record['cancel_attention_count'];
            //总成交金额
            $totalData['totalTurnover'] = ($totalData['totalTurnover'] ?? 0) + $record['turnover'];

            /** 每个编号对应的数据汇总 */
            $numberData[$record[$oneDimensionalKey]]['numberCount'] = ($numberData[$record[$oneDimensionalKey]]['numberCount'] ?? 0) + 1;
            $numberData[$record[$oneDimensionalKey]]['testFansNum'] = ($numberData[$record[$oneDimensionalKey]]['testFansNum'] ?? 0) + $record['fans_num'];
            $numberData[$record[$oneDimensionalKey]]['actualCost'] = ($numberData[$record[$oneDimensionalKey]]['actualCost'] ?? 0) + $actualCost;
            $numberData[$record[$oneDimensionalKey]]['conversionRate'] = ($numberData[$record[$oneDimensionalKey]]['conversionRate'] ?? 0) + $record['conversion_rate'];
            $numberData[$record[$oneDimensionalKey]]['underSeventeenCount'] = ($numberData[$record[$oneDimensionalKey]]['underSeventeenCount'] ?? 0) + $record['under_seventeen_count'];
            $numberData[$record[$oneDimensionalKey]]['silenceCount'] = ($numberData[$record[$oneDimensionalKey]]['silenceCount'] ?? 0) + $record['silence_count'];
            $numberData[$record[$oneDimensionalKey]]['cancelAttentionCount'] = ($numberData[$record[$oneDimensionalKey]]['cancelAttentionCount'] ?? 0) + $record['cancel_attention_count'];
            $numberData[$record[$oneDimensionalKey]]['turnover'] = ($numberData[$record[$oneDimensionalKey]]['turnover'] ?? 0) + $record['turnover'];
        }

        foreach ($numberData as $number => $val) {
            /** 一维以编号（大投/测试编号）为键 */
            //测试进粉数
            $list[$number]['testFansNum'] = $val['testFansNum'];
            //测试实际成本
            $list[$number]['actualCost'] = $val['actualCost'];
            //粉单
            $list[$number]['fansPrice'] = empty($val['testFansNum']) ? 0 : round($val['actualCost'] / $val['testFansNum'], 2);
            //转化率
            $list[$number]['conversionRate'] = empty($val['numberCount']) ? 0 : round($val['conversionRate'] / $val['numberCount'], 2);
            //17对以下占比
            $list[$number]['underSeventeenProportion'] = empty($val['testFansNum']) ? 0 : round($val['underSeventeenCount'] / $val['testFansNum'], 2);
            //沉默率
            $list[$number]['silenceRate'] = empty($val['testFansNum']) ? 0 : round($val['silenceCount'] / $val['testFansNum'], 2);
            //取关率
            $list[$number]['cancelAttentionRate'] = empty($val['testFansNum']) ? 0 : round($val['cancelAttentionCount'] / $val['testFansNum'], 2);
            //ROI
            $list[$number]['roi'] = empty($val['actualCost']) ? 0 : round($val['turnover'] / $val['actualCost'], 2);
            //变现
            $list[$number]['realization'] = empty($val['testFansNum']) ? 0 : round($val['turnover'] / $val['testFansNum'], 2);
        }

        return [$list, $totalData];
    }

    /**
     * 获取编号数据及总数据
     * @param array $testList
     * @param array $bigList
     * @param array $rebateList
     * @return array
     * @author dengkai
     * @date   2019/10/14
     */
    public function getNumberDataAndTotalData(array $testList, array $bigList, array $rebateList): array
    {
        $totalKey = ['totalActualCost', 'totalTestFansNum', 'totalUnderSeventeenCount', 'totalSilenceCount', 'totalCancelAttentionCount', 'totalTurnover'];
        $testData = $bigData = $testTotalData = $bigTotalData = $totalData = [];

        if (!empty($testList)) {
            [$testData, $testTotalData] = $this->dealWithTestMktadData($testList, 'test_number', $rebateList);
        }

        if (!empty($bigList)) {
            [$bigData, $bigTotalData] = $this->dealWithTestMktadData($bigList, 'delivery_number', $rebateList);
        }

        $lists = array_merge($testData, $bigData);

        /** 统计全部记录数据总和 */
        foreach ($totalKey as $key) {
            $totalData[$key] = ($testTotalData[$key] ?? 0) + ($bigTotalData[$key] ?? 0);
        }

        return [$lists, $totalData];
    }


    /**
     * 获取查询日期
     * @param Model $videoStatisticsDto
     * @return array
     * @author dengkai
     * @date   2019/9/27
     */
    public function getQueryDate(Model $videoStatisticsDto): array
    {
        if (empty($videoStatisticsDto->specifiedTime)) {
            $timeStampArr = ['beginTime' => $videoStatisticsDto->getInputBeginTime(), 'endTime' => $videoStatisticsDto->getInputEndTime()];
        } else {
            $arr = $videoStatisticsDto->getSpecifiedTime();
            $timeStampArr = ['beginTime' => $arr['beginTime'], 'endTime' => $arr['endTime']];
        }

        return $timeStampArr;
    }

    /**
     * 处理视频统计渠道投放量详情数据
     * @param array $putDataList
     * @param array $rebateList
     * @param array $videoName
     * @return array
     * @author dengkai
     * @date   2019/10/6
     */
    private function setVideoStatisticsPutData(array $putDataList, array $rebateList, array $videoName): array
    {
        $list = $detail = [];
        foreach ($putDataList as $key => $record) {
            $list[$record['v_id']]['videoId'] = $record['v_id'];
            $list[$record['v_id']]['videoName'] = $videoName[$record['v_id']];

            //实际成本=消耗/返点
            $actualCost = round($record['consume'] / $rebateList[$record['r_id']]['rebate'], 2);

            /** 统计渠道投放量详情（同一个编号可能存在相同的返点的数据，相同的返点则数据进行相加） */
            $rebateName = $rebateList[$record['r_id']]['channel_name'];

            if (preg_match('/[(|（，]+/u', $rebateName)) {
                $rebateName = preg_split('/[(|（]+/u', $rebateName)[0];
            }

            //消耗
            $consumeTotal = $detail[$record['v_id']]['channelPutDetail']
            [$rebateName]['consume'] = ($detail[$record['v_id']]['channelPutDetail']
                    [$rebateName]['consume'] ?? 0) + $record['consume'];
            //实际成本
            $actualCostTotal = $detail[$record['v_id']]['channelPutDetail']
            [$rebateName]['actualCost'] = ($detail[$record['v_id']]['channelPutDetail']
                    [$rebateName]['actualCost'] ?? 0) + $actualCost;

            //渠道投放量详情
            $list[$record['v_id']]['channelPutDetail'][$rebateName] = $rebateName . '--' . '消耗：' . $consumeTotal . '--' . '实际成本：' . $actualCostTotal;

            //实际转化数(实际进粉数)=DC转化数*（1-漏粉率）  数据库取出的漏粉数要先除以100
            $list[$record['v_id']]['totalFansNum'] = (string)(($list[$record['v_id']]['totalFansNum'] ?? 0) + round($record['dc_conversion_rate'] * (1 - $record['leakage_rate'] / 100), 2));
            //实际总成本 (float)number_format()处理方式主要是为了解决传到前端精度丢失问题
            $list[$record['v_id']]['totalActualCost'] = (string)(($list[$record['v_id']]['totalActualCost'] ?? 0) + $actualCost);
        }

        return $list;
    }

    /**
     * 处理视频统计后面字段的数据统计
     * @param array $homePageList
     * @param array $dataLists
     * @param array $testList
     * @param array $bigList
     * @param array $rebateList
     * @return array
     * @author dengkai
     * @date   2019/10/6
     */
    private function setVideoStatisticsTestMktadData(array $homePageList, array $dataLists, array $testList, array $bigList, array $rebateList): array
    {
        [$lists, $totalData] = $this->getNumberDataAndTotalData($testList, $bigList, $rebateList);

        /**
         * 投放数据可能出现同个视频对应有重复的“大投/测试编号”，
         * 因此此循环用于筛掉视频对应重复的“大投/测试编号”
         */
        $videoIdToNumberArr = [];
        foreach ($dataLists as $record) {
            $videoIdToNumberArr[$record['v_id']][$record['number']] = $record['number'];
        }

        //首页后几个字段key
        $keyArr = ['testFansNum', 'actualCost', 'underSeventeenProportion', 'silenceRate', 'cancelAttentionRate', 'roi', 'realization'];
        foreach ($videoIdToNumberArr as $videoId => $numberArr) {
            foreach ($numberArr as $number) {
                if (isset($lists[$number])) {
                    foreach ($keyArr as $key) {
                        $homePageList[$videoId][$key] = (string)(($homePageList[$videoId][$key] ?? 0) + $lists[$number][$key]);
                    }
                } else {
                    foreach ($keyArr as $key) {
                        $homePageList[$videoId][$key] = '0';
                    }
                }
            }
        }

        /** 数据汇总 */
        $homePageList = array_values($homePageList);
        $listCount = count($homePageList);
        $keyArr = ['totalFansNum', 'totalActualCost', 'testFansNum', 'actualCost'];

        $homePageList[$listCount]['videoId'] = '0';
        $homePageList[$listCount]['videoName'] = '汇总';
        $homePageList[$listCount]['channelPutDetail'] = '/';

        foreach ($keyArr as $val) {
            if (in_array($val, ['totalFansNum', 'totalActualCost', 'testFansNum', 'actualCost'])) {
                $homePageList[$listCount][$val] = (string)array_sum(array_column($homePageList, $val));
            }
        }

        $homePageList[$listCount]['underSeventeenProportion'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalUnderSeventeenCount'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['silenceRate'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalSilenceCount'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['cancelAttentionRate'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalCancelAttentionCount'] / $totalData['totalTestFansNum'], 2));
        $homePageList[$listCount]['roi'] = (string)(empty($totalData['totalActualCost']) ? 0 : round($totalData['totalTurnover'] / $totalData['totalActualCost'], 2));
        $homePageList[$listCount]['realization'] = (string)(empty($totalData['totalTestFansNum']) ? 0 : round($totalData['totalTurnover'] / $totalData['totalTestFansNum'], 2));

        return $homePageList;
    }

    /**
     * 导出视频统计首页数据
     * @param VideoStatisticsDto $videoStatisticsDto
     * @return array
     * @throws Exception
     * @author dengkai
     * @date   2019/10/7
     */
    public function exportVideoStatisticData(VideoStatisticsDto $videoStatisticsDto): array
    {
        $array = $this->getVideoStatisticsHomePageData($videoStatisticsDto, true);

        $data = $array['list'];
        foreach ($data as &$record) {
            $record['channelPutDetail'] = is_array($record['channelPutDetail']) ? implode(PHP_EOL, $record['channelPutDetail']) : $record['channelPutDetail'];
            unset($record['videoId']);
        }

        $tableHeader = ['视频', '渠道投放量详情', '总进粉数', '实际总成本', '测试进粉数', '测试实际总成本', '17岁以下占比', '沉默率', '取关率', 'ROI', '变现'];

        ExcelFacade::export(array_merge([$tableHeader], $data), '视频统计首页数据', ['B']);
        return ['path' => []];
    }

    /**
     * 导出视频测试详情首页数据
     * @param VideoStatisticsDto $videoStatisticsDto
     * @return array
     * @author dengkai
     * @date   2019/10/7
     */
    public function exportVideoTestDetailData(VideoStatisticsDto $videoStatisticsDto): array
    {
        $array = $this->getVideoTestDetailHomePageData($videoStatisticsDto);

        $data = $array['list'];
        foreach ($data as &$record) {
            $record['channelPutDetail'] = is_array($record['channelPutDetail']) ? implode(';', $record['channelPutDetail']) : $record['channelPutDetail'];
        }

        $tableHeader = ['大投/测试编号', '跟进人', '渠道投放量详情', '测试进粉数', '测试实际成本', '粉单', '转化率', '17岁以下占比', '沉默率', '取关率', 'ROI', '变现',];

        ExcelFacade::export(array_merge([$tableHeader], $data), '视频测试详情首页数据',['C']);

        return ['path' => []];
    }
}