<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;

use app\common\infrastructure\dto\SingleMessageDto;
use app\common\infrastructure\service\SMS;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\models\dataObject\SectionRealtimeMsgDo;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\enum\MessageEnum;
use app\modules\v1\autoConvert\enum\SectionRealtimeMsgEnum;
use app\modules\v1\autoConvert\service\AutoConvertSectionRealtimeMsgService;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\service\CalculateLackFansRateService;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception;

/**
 * @property RedisUtils                           $redisUtils
 * @property CalculateLackFansRateService         $calculateLackFansRateService
 * @property autoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService
 * Class AutoConvertService
 */
class AutoConvertServiceImpl extends BaseObject implements AutoConvertService
{
    /** @var RedisUtils */
    public $redisUtils;
    /** @var CalculateLackFansRateService */
    public $calculateLackFansRateService;
    /** @var AutoConvertSectionRealtimeMsgService */
    public $autoConvertSectionRealtimeMsgService;

    public function __construct(RedisUtils $redisUtils,
                                AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService,
                                CalculateLackFansRateService $calculateLackFansRateService,
                                $config = [])
    {
        $this->redisUtils                           = $redisUtils;
        $this->autoConvertSectionRealtimeMsgService = $autoConvertSectionRealtimeMsgService;
        $this->calculateLackFansRateService         = $calculateLackFansRateService;
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
        return in_array($convertRequestInfo->department, ['IRYfamily', 'IRYclub', 'irylover', 'iryskin', 'irycore'], true);
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
            (bool)$redis->hexists(MessageEnum::DC_REAL_TIME_MESSAGE, SectionRealtimeMsgEnum::getCurrentDept(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG)) === false) {
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
     * @param string $dept
     * @return int
     * @author dengkai
     * @date   2019/4/19
     */
    public function getThirtyMinFans(string $dept): int
    {
        if ($this->redisUtils->getRedis()->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $dept . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE))) {
            $timeStamp = $this->redisUtils->getRedis()->get(MessageEnum::DC_REAL_TIME_MESSAGE . $dept . MessageEnum::getTime(MessageEnum::DC_REAL_TIME_MESSAGE));
            $timeRange = $this->getTimeRange((int)$timeStamp);
            $nowTime   = time();
            if ($timeRange['beginTime'] <= $nowTime && $nowTime <= $timeRange['endTime']) {
                $currentFansCount        = $this->redisUtils->getRedis()->get(MessageEnum::DC_REAL_TIME_MESSAGE . $dept . MessageEnum::getCurrent(MessageEnum::DC_REAL_TIME_MESSAGE));
                $currentThirtyMinInitVal = $this->redisUtils->getRedis()->get(MessageEnum::DC_REAL_TIME_MESSAGE . $dept . MessageEnum::getHalfHour(MessageEnum::DC_REAL_TIME_MESSAGE));
                return (int)$currentFansCount - (int)$currentThirtyMinInitVal;
            }
            return 0;
        }
        return 0;
    }

    /**
     * 今日进粉数达到设置的今日供粉数时发送信息
     * @param ConvertRequestVo                     $convertRequestInfo
     * @param SMS                                  $SMS
     * @param AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService
     * @throws GuzzleException
     * @author zhuozhen
     */
    public function sendMessageWhenArriveTodayFansCount(ConvertRequestVo $convertRequestInfo, SMS $SMS, AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService): void
    {
        //当今日进粉数达到设置的今日供粉数，则发送一条消息
        if ($this->redisUtils->getRedis()->exists(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . '_fullFansInform')) {
            return;
        }
        $todayFansCount = $this->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getTodaySupportFans(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        $isMsgInform    = $this->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, SectionRealtimeMsgEnum::getIsMsgInform(SectionRealtimeMsgEnum::SECTION_REALTIME_MSG));
        if ($convertRequestInfo->fansCount >= $todayFansCount && $isMsgInform === 'yes') {
            //满粉通知标志     通知过一次之后设置此标志，今日都不再进行通知
            $this->redisUtils->getRedis()->set(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . '_fullFansInform', 'yes');
            $this->redisUtils->getRedis()->expireAt(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department . '_fullFansInform', $convertRequestInfo->department);
            //发送信息
            $currentDeptId   = $this->redisUtils->getRedis()->hGet(MessageEnum::DC_REAL_TIME_MESSAGE . $convertRequestInfo->department, 'id');
            $currentDeptInfo = $autoConvertSectionRealtimeMsgService->findOne(['id' => $currentDeptId]);

            try {
                $singleMessageDto         = new SingleMessageDto();
                $singleMessageDto->text   = '【供粉系统通知】您好，满粉通知！' . $currentDeptInfo['currentDept'] . '分部当天的进粉量已经达到指定日供粉额了，请马上前往处理和调整，避免分部爆粉，谢谢！';
                $singleMessageDto->mobile = $currentDeptInfo['control_member_phone'];
                $singleMessageDto->apikey = Yii::$app->params['api']['yunpian_api']['apikey'];
                $SMS->singleSendMsg($singleMessageDto);
                if ($currentDeptInfo['control_member_phone'] !== $currentDeptInfo['adminstrator_phone']) {
                    $singleMessageDto->mobile = $currentDeptInfo['adminstrator_phone'];
                    $SMS->singleSendMsg($singleMessageDto);
                }
            } catch (Exception $exception) {
                Yii::info($exception->getMessage());
            }
        }
    }


}