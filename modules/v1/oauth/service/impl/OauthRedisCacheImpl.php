<?php

namespace app\modules\v1\oauth\service\impl;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use app\common\exception\RedisException;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\models\dataObject\StaticConversionDo;
use app\modules\v1\oauth\service\OauthCacheService;
use Yii;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property RedisUtils $redisUtils
 * @property ArrayUtils $arrayUtils
 * @property StaticConversionDo $staticConversion
 * @author: lirong
 */
class OauthRedisCacheImpl extends BaseObject implements OauthCacheService
{
    /* @var RedisUtils */
    protected $redisUtils;
    /* @var ArrayUtils */
    protected $arrayUtils;

    /**
     * OauthRedisCacheServiceImpl constructor.
     *
     * @param RedisUtils $redisUtils
     * @param ArrayUtils $arrayUtils
     * @param array $config
     */
    public function __construct(RedisUtils $redisUtils, ArrayUtils $arrayUtils, $config = [])
    {
        $this->redisUtils = $redisUtils;
        $this->arrayUtils = $arrayUtils;
        parent::__construct($config);
    }

    /**
     * 缓存 - 缓存token
     *
     * @param OauthDto $oauthDto
     * @return void
     * @throws RedisException
     * @author: lirong
     */
    public function cacheToken(OauthDto $oauthDto): void
    {
        $this->redisUtils->getRedis()->hdel(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'], [$oauthDto->authorizer_info->account_id]);
        if (!$this->redisUtils->getRedis()->set(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'] . $oauthDto->authorizer_info->account_id,
            json_encode(ArrayUtils::attributesAsMap($oauthDto)))) {
            throw new RedisException('设置token缓存失败!', 500);
        }
        //设置过期时间:以refresh_token 过期时间设置
        $this->redisUtils->getRedis()->expire(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'] . $oauthDto->authorizer_info->account_id,
            $oauthDto->refresh_token_expires_in);
    }
}
