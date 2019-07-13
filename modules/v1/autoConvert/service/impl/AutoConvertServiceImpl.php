<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;

use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\models\dataObject\SectionRealtimeMsgDo;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\enum\MessageEnum;
use app\modules\v1\autoConvert\enum\SectionRealtimeMsgEnum;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\service\CalculateLackFansRateService;
use Predis\Client;
use yii\base\BaseObject;

/**
 * @property RedisUtils                   $redisUtils
 * @property CalculateLackFansRateService $calculateLackFansRateService
 * Class AutoConvertService
 */
class AutoConvertServiceImpl extends BaseObject implements AutoConvertService
{
    /** @var RedisUtils */
    public $redisUtils;
    /** @var CalculateLackFansRateService */
    public $calculateLackFansRateService;

    public function __construct(RedisUtils $redisUtils,
                                CalculateLackFansRateService $calculateLackFansRateService,
                                $config = [])
    {
        $this->redisUtils                   = $redisUtils;
        $this->calculateLackFansRateService = $calculateLackFansRateService;
        parent::__construct($config);
    }

    /**
     * 检查公众号是否存在
     * @param ConvertRequestVo $convertRequestInfo
     * @return bool
     * @author zhuozhen
     */
    public function checkDeptExists(ConvertRequestVo $convertRequestInfo): bool
    {
        return in_array($convertRequestInfo->department,['IRYfamily', 'IRYclub', 'irylover', 'iryskin', 'irycore'], true);
    }


    /**
     * 判断当前公众号的上次的时间戳和粉丝数是否存在redis中
     * 不存在则证明该公众号第一次进粉
     * @param ConvertRequestVo $convertRequestInfo
     */
    public function prepareData(ConvertRequestVo $convertRequestInfo): void
    {
        $redis         = $this->redisUtils->getRedis();
        $timeStampBool = $redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE));
        $fansCountBool = $redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE));
        $todayEndTime  = mktime(23, 59, 59, (int)date('m'), (int)date('d'), (int)date('Y'));

        if ((bool)$timeStampBool === false && (bool)$fansCountBool === false) {
            //当前粉丝数时间戳
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE), time());                //当前粉丝数
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE), $convertRequestInfo->fansCount);
            //当前30分钟粉丝数初始值
            $redis->set(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE), 0);
            //设置过期时间
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE), $todayEndTime);
        }
    }

    /**
     * 初始化部门信息
     * @param ConvertRequestVo $convertRequestInfo
     * @author zhuozhen
     */
    public function initDept(ConvertRequestVo $convertRequestInfo): void
    {
        $redis = $this->redisUtils->getRedis();
        if ((bool)$redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department) === false ||
            (bool)$redis->hexists(MessageEnum::DC_REAL_TIME_MESSAGE, SectionRealtimeMsgEnum::getCurrentDept(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG)) === false)
        {
            $sectionRealtimeMsgInfo = SectionRealtimeMsgDo::find()->where('BINARY current_dept = :current_dept', [':current_dept' => $convertRequestInfo->department])->one();
            if ($sectionRealtimeMsgInfo === null) {
                return;
            }
            $redis->hMSet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, ArrayUtils::attributesAsMap($sectionRealtimeMsgInfo));

            //设置当前半小时的最后一秒为过期时间
            $expirationTime = $this->getTimeRange(time());
            $redis->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, $expirationTime['endTime']);

        }
        $redis->hSet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, 'todayFansCount', $convertRequestInfo->fansCount);

    }


    /**
     * 获取公众号已存储时间戳的分钟数所属半小时范围
     * @param int $timeStamp
     * @return array
     * @author dengkai
     * @date   2019/4/19
     */
    public function getTimeRange(int $timeStamp): array
    {
        $minute = (int)date('i', $timeStamp);
        if ($minute < 30) {
            $beginTime = mktime((int)date('H', $timeStamp), 0, 0, (int)date('m', $timeStamp), (int)date('d', $timeStamp), (int)date('Y', $timeStamp));
            $endTime   = mktime((int)date('H', $timeStamp), 30, 0, (int)date('m', $timeStamp), (int)date('d', $timeStamp), (int)date('Y', $timeStamp));

            return ['beginTime' => $beginTime, 'endTime' => $endTime];
        }

        $beginTime = mktime((int)date('H', $timeStamp), 30, 0, (int)date('m', $timeStamp), (int)date('d', $timeStamp), (int)date('Y', $timeStamp));
        $endTime   = mktime((int)date('H', $timeStamp), 59, 59, (int)date('m', $timeStamp), (int)date('d', $timeStamp), (int)date('Y', $timeStamp));

        return ['beginTime' => $beginTime, 'endTime' => $endTime];
    }


    /**
     * 获取当前30分钟的粉丝数
     * exp:如果现在是25分，则当前三十分钟是指0—30分。
     * @param $redis              Client
     * @param $convertRequestInfo ConvertRequestVo
     * @return int
     * @author dengkai
     * @date   2019/4/19
     */
    public function getThirtyMinFans(Client $redis, ConvertRequestVo $convertRequestInfo): int
    {
        if ($redis->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE))) {
            $timeStamp = $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE));
            $timeRange = $this->getTimeRange((int)$timeStamp);
            $nowTime   = time();
            if ($timeRange['beginTime'] <= $nowTime && $nowTime <= $timeRange['endTime']) {
                $currentFansCount        = $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE));
                $currentThirtyMinInitVal = $redis->get(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE));
                return (int)$currentFansCount - (int)$currentThirtyMinInitVal;
            }
            return 0;
        }
        return 0;
    }


}