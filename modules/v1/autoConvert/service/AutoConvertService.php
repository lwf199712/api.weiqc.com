<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service;

use app\common\utils\RedisUtils;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use Predis\Client;

/**
 * @property StaticUrlDo                  $staticUrlDo
 * @property RedisUtils                   $redisUtils
 * @property CalculateLackFansRateService $calculateLackFansRateService
 * Class AutoConvertService
 */
interface AutoConvertService
{
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
     * @param $redis              Client
     * @param $convertRequestInfo ConvertRequestVo
     * @return int
     * @author dengkai
     * @date   2019/4/19
     */
    public function getThirtyMinFans(Client $redis, ConvertRequestVo $convertRequestInfo): int;

}