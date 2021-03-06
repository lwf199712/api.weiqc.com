<?php declare(strict_types=1);

namespace app\modules\v1\userAction\service\impl;

use app\common\exception\RedisException;
use app\common\utils\RedisUtils;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;
use app\daemon\course\urlConvert\domain\dto\RedisUrlConvertDto;
use app\models\dataObject\StaticConversionDo;
use app\modules\v1\userAction\enum\ConversionEnum;
use app\modules\v1\userAction\enum\UrlConvertEnum;
use app\modules\v1\userAction\service\UserActionCache;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property RedisUtils $redisUtils
 * @property StaticConversionDo $staticConversion
 * @author: lirong
 */
class UserActionRedisCacheImpl extends BaseObject implements UserActionCache
{
    /* @var RedisUtils */
    protected $redisUtils;
    /**
     * OauthRedisCacheServiceImpl constructor.
     *
     * @param RedisUtils $redisUtils
     * @param array $config
     */
    public function __construct(RedisUtils $redisUtils, $config = [])
    {
        $this->redisUtils = $redisUtils;
        parent::__construct($config);
    }

    /**
     * 缓存用户行为 - 浏览(独立ip记录)
     *
     * @param RedisAddViewDto $redisAddViewDto
     * @return void
     * @throws RedisException
     * @author: lirong
     */
    public function addViews(RedisAddViewDto $redisAddViewDto): void
    {
        //redis存储(从队列头插入)
        if (!$this->redisUtils->getRedis()->lpush(ConversionEnum::REDIS_ADD_VIEW, [json_encode($redisAddViewDto->attributes)])) {
            throw new RedisException('push list false', 500);
        }
    }

    /**
     * 缓存用户行为 - 短链转长链（独立IP记录）
     * @param RedisUrlConvertDto $redisUrlConvertDto
     * @throws RedisException
     */
    public function addUrConvertHits(RedisUrlConvertDto $redisUrlConvertDto) : void
    {
        if (!$this->redisUtils->getRedis()->lpush(UrlConvertEnum::REDIS_URL_CONVERT_IP, [json_encode($redisUrlConvertDto->attributes)])){
            throw new RedisException('push list false', 500);
        }
    }

    /**
     * 缓存用户行为 - 短链转长链（独立访客记录）
     * @param RedisUrlConvertDto $redisUrlConvertDto
     * @throws RedisException
     */
    public function addUrConvertClient(RedisUrlConvertDto $redisUrlConvertDto) : void
    {
        if (!$this->redisUtils->getRedis()->lpush(UrlConvertEnum::REDIS_URL_CONVERT_CLIENT, [json_encode($redisUrlConvertDto->attributes)])){
            throw new RedisException('push list false', 500);
        }
    }

    /**
     * 缓存用户行为 - 短链转长链（访问数据）
     * @param RedisUrlConvertDto $redisUrlConvertDto
     * @throws RedisException
     */
    public function addUrConvertVisit(RedisUrlConvertDto $redisUrlConvertDto) : void
    {
        if (!$this->redisUtils->getRedis()->lpush(UrlConvertEnum::REDIS_URL_CONVERT_VISIT, [json_encode($redisUrlConvertDto->attributes)])){
            throw new RedisException('push list false', 500);
        }
    }
}
