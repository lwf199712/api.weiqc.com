<?php

namespace app\modules\v1\oauth\service\impl;

use app\common\exception\RedisException;
use app\common\utils\RedisUtils;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;
use app\models\dataObject\StaticConversionDo;
use app\modules\v1\oauth\domain\vo\AuthorizationTokenDto;
use app\modules\v1\oauth\domain\vo\AuthorizeResponseVo;
use app\modules\v1\oauth\service\OauthCacheService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property RedisUtils $redisUtils
 * @property StaticConversionDo $staticConversion
 * @author: lirong
 */
class OauthRedisCacheImpl extends BaseObject implements OauthCacheService
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
     * TODO 缓存 - 缓存token
     *
     * @param AuthorizationTokenDto $authorizationTokenDto
     * @return void
     * @author: lirong
     */
    public function cacheToken(AuthorizationTokenDto $authorizationTokenDto): void
    {

    }
}
