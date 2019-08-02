<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\domain\entity;


use app\models\dataObject\StaticServiceConversionsDo;
use app\modules\v1\userAction\enum\ServiceConversionPatternEnum;
use yii\base\Exception;

class StaticServiceConversionEntity extends StaticServiceConversionsDo
{
    public const SERVICE_CONVERSION = 'ServiceConversion';

    public function rules(): array
    {
        return array_merge(parent::rules(),[
            [['service'],'required','on' => self::SERVICE_CONVERSION ,'message' => '链接公众号没有填写']
        ]);
    }

    /**
     * 解析服务号数据
     * @return StaticServiceConversionEntity
     * @author zhuozhen
     */
    public function parserServiceInfo(): self
    {
        if (!empty($this->service_list)) {
            $service_list           = explode(',', $this->service_list);
            $conversions_list       = explode(',', $this->conversions_list);
            $this->service_list     = $service_list;
            $this->conversions_list = $conversions_list;
        }
        return $this;
    }

    /**
     * 计算转换后的结果
     * @param StaticServiceConversionEntity $staticServiceConversionEntity
     * @return string
     * @throws Exception
     * @author zhuozhen
     */
    public function getResultOfConvert(StaticServiceConversionEntity $staticServiceConversionEntity): string
    {
        switch ($staticServiceConversionEntity->pattern) {
            case ServiceConversionPatternEnum::NOT_CIRCLE_PATTERN:
                return $this->calculateProcessOfNotCirclePattern($staticServiceConversionEntity);
            case ServiceConversionPatternEnum::HOUR_CIRCLE_PATTERN:
                return $this->calculateProcessOfHourCirclePattern($staticServiceConversionEntity);
            case ServiceConversionPatternEnum::TURNOVER_NUMBER_CIRCLE_PATTERN:
                return $this->calculateProcessOfTurnoverNumberCirclePattern($staticServiceConversionEntity);
            case ServiceConversionPatternEnum::AUTO_CONVERT_PATTERN:
                return $this->calculateProcessOfAutoConvertPattern($staticServiceConversionEntity);
        }
    }

    /**
     * 计算不循环模式结果(不作处理直接返回)
     * @param StaticServiceConversionEntity $staticServiceConversionEntity
     * @return string
     * @author zhuozhen
     */
    private function calculateProcessOfNotCirclePattern(StaticServiceConversionEntity $staticServiceConversionEntity): string
    {
        return $staticServiceConversionEntity->service;
    }

    /**
     * 计算按小时循环模式结果
     * @param StaticServiceConversionEntity $staticServiceConversionEntity
     * @return string
     * @throws Exception
     * @author zhuozhen
     */
    private function calculateProcessOfHourCirclePattern(StaticServiceConversionEntity $staticServiceConversionEntity): string
    {
        if (empty($staticServiceConversionEntity->conversions_list)) {
            throw new Exception('链接公众号列表没有填写');
        }
        $list           = $this->getConversionsList($staticServiceConversionEntity);
        $currentService = trim($staticServiceConversionEntity->service);
        $rank           = $this->getServiceRank($list, $currentService);
        #判断有没有是投放时间，有投放时间且大于1小时的增加rank
        if (empty($staticServiceConversionEntity->conversions_time) === false || time() - $staticServiceConversionEntity->conversions_time >= 3600) {
            $rank++;
        } else if ($staticServiceConversionEntity->conversions >= $list[$rank]['conversions']) {    #如果大于转换目标也转换
            $rank++;
        }
        if ($rank >= count($list)) {
            $rank = 0;
        }
        return $list[$rank]['account'];
    }

    /**
     * 计算按转换数循环模式结果
     * @param StaticServiceConversionEntity $staticServiceConversionEntity
     * @return string
     * @throws Exception
     * @author zhuozhen
     */
    private function calculateProcessOfTurnoverNumberCirclePattern(StaticServiceConversionEntity $staticServiceConversionEntity): string
    {
        if (empty($staticServiceConversionEntity->conversions_list)) {
            throw new Exception('链接公众号列表没有填写');
        }
        $list           = $this->getConversionsList($staticServiceConversionEntity);
        $currentService = trim($staticServiceConversionEntity->service);
        $rank           = $this->getServiceRank($list, $currentService);
        if ($staticServiceConversionEntity->conversions >= $list[$rank]['conversions']) {    #大于转换目标转换
            $rank++;
        }
        if ($rank >= count($list)) {
            $rank = 0;
        }
        return $list[$rank]['account'];
    }

    /**
     * 计算按自动转粉模式结果（自动转粉采用其他接口，此处直接返回不作处理）
     * @param StaticServiceConversionEntity $staticServiceConversionEntity
     * @return string
     * @author zhuozhen
     */
    private function calculateProcessOfAutoConvertPattern(StaticServiceConversionEntity $staticServiceConversionEntity): string
    {
        return $staticServiceConversionEntity->service;
    }

    /**
     * 获取公众号 转化数列表
     * @param StaticServiceConversionEntity $staticServiceConversionEntity
     * @return array
     * @author zhuozhen
     */
    private function getConversionsList(StaticServiceConversionEntity $staticServiceConversionEntity): array
    {
        $conList         = [];
        $conversionsList = (array)$staticServiceConversionEntity->service_list;
        foreach ($conversionsList as $key => $account) {
            $conList[] = [
                'account'     => $account,
                'conversions' => $staticServiceConversionEntity->conversions_list[$key],
            ];
        }
        return $conList;
    }

    /**
     * 获取当前服务号排名
     * @param array  $list
     * @param string $currentService
     * @return false|int|string
     * @author zhuozhen
     */
    private function getServiceRank(array $list, string $currentService)
    {
        return array_search($currentService, array_column($list, 'account'), false);
    }

    /**
     * 更新公众号信息
     * @param int    $uid
     * @param string $urlService
     * @return bool
     * @author zhuozhen
     */
    public function updateConversions(int $uid, string $urlService): bool
    {
        $result = $this::updateAll(['conversion' => 0, 'conversion_time' => 0, 'service' => $urlService], ['u_id' => $uid]);
        return $result > 0;
    }

}