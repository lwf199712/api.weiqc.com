<?php

namespace app\modules\v1\oauth\service\impl;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use app\common\utils\RedisUtils;
use app\models\dataObject\StaticConversionDo;
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
     * 缓存 - 缓存token
     *
     * @param OauthDto $oauthDto
     * @return void
     * @author: lirong
     */
    public function cacheToken(OauthDto $oauthDto): void
    {
        var_dump($oauthDto);
        exit;
        // TODO: Implement cacheToken() method.
    }
}
