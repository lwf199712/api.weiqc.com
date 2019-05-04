<?php

namespace app\api\tencentMarketingApi\oauth\service\impl;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenAuthorizerInfoResponseDto;
use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenResponseDto;
use app\api\tencentMarketingApi\oauth\service\OauthCacheService;
use app\common\exception\RedisException;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\models\dataObject\StaticConversionDo;
use Predis\Connection\ConnectionException;
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
     * @param OauthTokenResponseDto $oauthDto
     * @throws RedisException|ConnectionException
     * @author: lirong
     */
    public function cacheToken(OauthTokenResponseDto $oauthDto): void
    {
        $oauthDto->create_at = time();//设置创建时间
        $this->redisUtils->getRedis()->hdel(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'], [$oauthDto->authorizer_info->account_id]);
        if (!$this->redisUtils->getRedis()->set(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'] . $oauthDto->authorizer_info->account_id,
            json_encode(ArrayUtils::attributesAsMap(clone $oauthDto)))) {
            throw new RedisException('设置token缓存失败!', 500);
        }
        //设置过期时间:以refresh_token 过期时间设置
        $this->redisUtils->getRedis()->expire(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'] . $oauthDto->authorizer_info->account_id,
            $oauthDto->refresh_token_expires_in);
    }


    /**
     * 缓存 - 获得token
     *
     * @param int $accountId
     * @return OauthTokenResponseDto|null
     * @author: lirong
     */
    public function getToken(int $accountId): ?OauthTokenResponseDto
    {
        //从redis中获取token
        $oauthRedisDto = $this->redisUtils->getRedis()->get(Yii::$app->params['oauth']['tencent_marketing_api']['token_key'] . $accountId);
        if ($oauthRedisDto) {
            $oauthRedisDto = json_decode($oauthRedisDto, true);
            $oauthDto = new OauthTokenResponseDto();
            $oauthDto->attributes = $oauthRedisDto;
            if ($oauthRedisDto['authorizer_info'] ?? false) {
                $oauthDto->authorizer_info = new OauthTokenAuthorizerInfoResponseDto();
                $oauthDto->authorizer_info->account_uin = $oauthRedisDto['authorizer_info']['account_uin'] ?? '';
                $oauthDto->authorizer_info->account_id = $oauthRedisDto['authorizer_info']['account_id'] ?? '';
                $oauthDto->authorizer_info->scope_list = $oauthRedisDto['authorizer_info']['scope_list'] ?? '';
            }
            return $oauthDto;
        }
        return null;
    }
}
