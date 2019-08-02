<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service;

use app\common\infrastructure\service\SMS;
use app\common\utils\RedisUtils;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;

/**
 * @property StaticUrlDo                  $staticUrlDo
 * @property RedisUtils                   $redisUtils
 * @property CalculateLackFansRateService $calculateLackFansRateService
 * Class AutoConvertService
 */
interface AutoConvertService
{

    /**
     * 检查公众号是否存在
     * @param ConvertRequestVo $convertRequestInfo
     * @return bool
     * @author zhuozhen
     */
    public function checkDeptExists(ConvertRequestVo $convertRequestInfo): bool;

    /**
     * 判断当前公众号的上次的时间戳和粉丝数是否存在redis中
     * 不存在则证明该公众号第一次进粉
     * @param ConvertRequestVo $convertRequestInfo
     */
    public function prepareData(ConvertRequestVo $convertRequestInfo): void;

    /**
     * 初始化部门信息
     * @param ConvertRequestVo $convertRequestInfo
     * @author zhuozhen
     */
    public function initDept(ConvertRequestVo $convertRequestInfo): void;

    /**
     * 获取公众号已存储时间戳的分钟数所属半小时范围
     * @param int $timeStamp
     * @return array
     * @author dengkai
     * @date   2019/4/19
     */
    public function getTimeRange(int $timeStamp): array;


    /**
     * 获取当前30分钟的粉丝数
     * exp:如果现在是25分，则当前三十分钟是指0—30分。
     * @param string $dept
     * @return int
     * @author dengkai
     * @date   2019/4/19
     */
    public function getThirtyMinFans(string $dept): int;

    /**
     * 今日进粉数达到设置的今日供粉数时发送信息
     * @param ConvertRequestVo                     $convertRequestInfo
     * @param SMS                                  $SMS
     * @param AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService
     * @author zhuozhen
     */
    public function sendMessageWhenArriveTodayFansCount(ConvertRequestVo $convertRequestInfo,SMS $SMS,AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService) :void ;


}