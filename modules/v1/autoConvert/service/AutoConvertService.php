<?php
declare(strict_types=1);

use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\models\dataObject\SectionRealtimeMsgDo;

/**
 * @property RedisUtils       $redisUtils
 * @property ConvertRequestVo $convertRequestInfo
 * Class AutoConvertService
 */
class AutoConvertService
{
    /** @var RedisUtils */
    protected $redisUtils;
    /** @var  ConvertRequestVo */
    protected $convertRequestInfo;


    public function __construct(RedisUtils $redisUtils, ConvertRequestVo $convertRequestInfo)
    {
        $this->redisUtils         = $redisUtils;
        $this->convertRequestInfo = $convertRequestInfo;
    }


    /**
     * 判断当前公众号的上次的时间戳和粉丝数是否存在redis中
     * 不存在则证明该公众号第一次进粉
     */
    public function prepareData(): void
    {
        $redis         = $this->redisUtils->getRedis();
        $timeStampBool = $redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE));
        $fansCountBool = $redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE) . $this->convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE);
        $todayEndTime  = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        if ((bool)$timeStampBool === false && (bool)$fansCountBool === false) {
            //当前粉丝数时间戳
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE), time());                //当前粉丝数
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE), $this->convertRequestInfo->fansCount);
            //当前30分钟粉丝数初始值
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE), 0);
            //设置过期时间
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
        }
    }

    /**
     * 初始化部门信息
     * @author zhuozhen
     */
    public function initDept(): void
    {
        $redis = $this->redisUtils->getRedis();
        if ($redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department) ||
            $redis->hexists(MessageEnum::DC_REAL_TIME_MESSAGE, SectionRealtimeMsgEnum::getCurrentDept(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG))) {
            $sectionRealtimeMsgInfo = SectionRealtimeMsgDo::findOne(['=', 'BINARY current_dept', $this->convertRequestInfo->department]);
            if ($sectionRealtimeMsgInfo === null) {
                return;
            }
            $redis->hMSet(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department, ArrayUtils::attributesAsMap($sectionRealtimeMsgInfo));

            //设置当前半小时的最后一秒为过期时间
            $expirationTime = $this->getTimeRange(time());
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department, $expirationTime['endTime']);

            //设定增加10%的粉丝数量，以便用来判定各公众号都满粉后的切换规则
            $fullFansCount = $sectionRealtimeMsgInfo['thirty_min_fans_target'] + ceil($sectionRealtimeMsgInfo['thirty_min_fans_target'] * 0.1);
            $redis->hSet(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department, 'fullFansCount', $fullFansCount);
        }
        $redis->hSet(MessageEnum::DC_REAL_TIME_MESSAGE . $this->convertRequestInfo->department, 'todayFansCount', $this->convertRequestInfo->fansCount);

    }


    /**
     * 获取公众号已存储时间戳的分钟数所属半小时范围
     * @param $timeStamp
     * @return array
     * @author dengkai
     * @date   2019/4/19
     */
    public function getTimeRange($timeStamp): array
    {
        $minute = (int)date('i', $timeStamp);
        if ($minute < 30) {
            $beginTime = mktime(date('H', $timeStamp), 0, 0, date('m', $timeStamp), date('d', $timeStamp), date('Y', $timeStamp));
            $endTime   = mktime(date('H', $timeStamp), 30, 0, date('m', $timeStamp), date('d', $timeStamp), date('Y', $timeStamp));

            return ['beginTime' => $beginTime, 'endTime' => $endTime];
        }

        $beginTime = mktime(date('H', $timeStamp), 30, 0, date('m', $timeStamp), date('d', $timeStamp), date('Y', $timeStamp));
        $endTime   = mktime(date('H', $timeStamp), 59, 59, date('m', $timeStamp), date('d', $timeStamp), date('Y', $timeStamp));

        return ['beginTime' => $beginTime, 'endTime' => $endTime];
    }
}