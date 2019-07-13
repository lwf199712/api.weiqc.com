<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;

use app\common\utils\ArrayUtils;
use app\models\dataObject\SectionRealtimeMsgDo;
use app\modules\v1\autoConvert\domain\event\AutoConvertEvent;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\enum\MessageEnum;
use app\modules\v1\autoConvert\enum\SectionRealtimeMsgEnum;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\service\CalculateLackFansRateService;
use Predis\Client;
use yii\base\BaseObject;

class CalculateLackFansRateServiceImpl extends BaseObject implements CalculateLackFansRateService
{

    /**
     * 计算出缺粉率最高的部门
     *
     * @param AutoConvertEvent $event
     * @param bool             $isFullFans 是否全部满粉后的重新计算
     * @return array|null ['lackFansDept' => 'xxx' , 'lackFansRate' => 'xxx' , 'availableDept' => 'xxx']. null will return  while not need change service
     * @author zhuozhen
     */
    public function calculateLackFansRate(AutoConvertEvent $event, bool $isFullFans): ?array
    {
        [$availableWhiteListDept, $lackFansRate, $lackFansDept, $whiteListLackFansFlag] = [[], 0, '', false];
        if (!empty($event->whiteList)) {
            foreach (explode(',', $event->whiteList) as $dept) {
                if ($event->redisUtils->getRedis()->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $dept)) {
                    $stopSupport         = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $dept, SectionRealtimeMsgEnum::getIsStopSupportFans(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
                    $thirtyMinFansTarget = $event->redisUtils->getRedis()->hget(MessageEnum::DC_REAL_TIME_MESSAGE . $dept, SectionRealtimeMsgEnum::getThirtyMinFansTarget(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
                    $currentDept         = $event->redisUtils->getRedis()->hget(MessageEnum::DC_REAL_TIME_MESSAGE . $dept, SectionRealtimeMsgEnum::getCurrentDept(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
                } else {
                    $record              = ArrayUtils::attributesAsMap(SectionRealtimeMsgDo::find()->where('BINARY current_dept = :current_dept', [':current_dept' => $dept])->one());
                    $stopSupport         = $record['is_stop_support_fans'];
                    $thirtyMinFansTarget = $record['thirty_min_fans_target'];
                    $currentDept         = $record['current_dept'];
                }
                if ($stopSupport === 'yes') {
                    continue;
                }
                if ($isFullFans === true) {      //如果是全部满粉后重新计算，则将增量后目标数作为三十分钟内目标数
                    $thirtyMinFansTarget = $event->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $dept, 'fullFansCount');
                }
                /** 白名单中满足进粉的分部 */
                $availableWhiteListDept[$dept]['thirty_min_fans_target'] = $thirtyMinFansTarget;
                $availableWhiteListDept[$dept]['current_dept']           = $currentDept;
                $whiteListLackFansFlag                                   = true;
            }
            [$lackFansDept, $lackFansRate, $availableDept,$allLackFansRate] = array_values($this->getLackFansRateAndDept($availableWhiteListDept, $event->redisUtils->getRedis(), $event->autoConvertService));
            /** 白名单不满粉,返回最高缺粉率分部 */
            if ($availableDept !== null && $lackFansRate !== 0 && $lackFansDept !== '') {
                return ['lackFansDept' => $lackFansDept, 'lackFansRate' => $lackFansRate, 'availableDept' => $availableDept,'allLackFansRate' => $allLackFansRate];
            }
        }

        $availableDept = SectionRealtimeMsgDo::find()
            ->where(['not in', 'current_dept', $event->convertRequestInfo->department])
            ->andWhere(['=', 'is_accept_distribute', 'yes'])
            ->andWhere(['=', 'is_stop_support_fans', 'no'])
            ->asArray()
            ->all();


        /** 白名单满粉且白名单外无可用分部 */
        if ($availableDept === null && $availableWhiteListDept !== null && $lackFansRate === 0 && $lackFansDept === '' && $whiteListLackFansFlag) {
            return ['lackFansDept' => $lackFansDept, 'lackFansRate' => $lackFansRate, 'availableDept' => array_merge($availableWhiteListDept, $event->convertRequestInfo->department), 'allLackFansRate' => $allLackFansRate ?? []];
        }
        /** 白名单无可用或无白名单 且 白名单外部无可用*/
        if ($availableDept === null && $whiteListLackFansFlag === false) {
            return null;
        }
        return $this->getLackFansRateAndDept($availableDept, $event->redisUtils->getRedis(), $event->autoConvertService);
    }

    /**
     * 获取最高缺粉率和缺粉部门
     * @param array              $availableDept
     * @param Client             $redis
     * @param AutoConvertService $autoConvertService
     * @return array
     * @author zhuozhen
     */
    private function getLackFansRateAndDept(array $availableDept, Client $redis, AutoConvertService $autoConvertService): array
    {
        [$lackFansRate, $lackFansDept,$allLackFansRate] = [0, '',[]];
        foreach ($availableDept as $dept) {
            //如果当前分部（公众号）存在redis中
            if ($redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $dept['current_dept'])) {
                //获取该分部当前30分钟的实际进粉数
                $thirtyMinFans = $autoConvertService->getThirtyMinFans($redis, $dept['current_dept']);
                //当前分部缺粉率（30分钟供粉目标-30分钟实际进粉数）/30分钟供粉目标
                $lackRate                                = ($dept['thirty_min_fans_target'] - $thirtyMinFans) / $dept['thirty_min_fans_target'];
                $deptLackFansRate[$dept['current_dept']] = round($lackRate, 2);
            } else {
                //该分部不存在redis中，说明该服务号还未进粉，缺粉率为100%
                $deptLackFansRate[$dept['current_dept']] = 1;
            }
            //存储所有部门缺粉率
            $allLackFansRate[$dept['current_dept']] =  isset($thirtyMinFans) ?
                '('. $dept['thirty_min_fans_target'] .' - '.$thirtyMinFans . ') /  '.  $dept['thirty_min_fans_target'] .' = '.$deptLackFansRate[$dept['current_dept']] : 1;

            //存储缺粉率及缺粉率最高的部门
            if ($lackFansRate < $deptLackFansRate[$dept['current_dept']]) {
                $lackFansRate = $deptLackFansRate[$dept['current_dept']];
                $lackFansDept = $dept['current_dept'];
            }
        }
        return ['lackFansDept' => $lackFansDept, 'lackFansRate' => $lackFansRate, 'availableDept' => $availableDept, 'allLackFansRate' => $allLackFansRate];
    }
}