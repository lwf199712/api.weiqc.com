<?php
namespace app\api\tencentMarketingAPI\oauth\service\impl;

use app\api\tencentMarketingAPI\oauth\service\OauthService;
use app\common\utils\RedisUtils;
use app\models\dataObject\StaticConversionDo;
use app\modules\v1\oauth\domain\vo\AuthorizationTokenDto;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property RedisUtils $redisUtils
 * @property StaticConversionDo $staticConversion
 * @author: lirong
 */
class OauthImpl extends BaseObject implements OauthService
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
     * 通过 Authorization Code 获取 Access Token 或刷新 Access Token
     *
     * @param AuthorizationTokenDto $authorizationTokenDto
     * @author: lirong
     */
    public function token(AuthorizationTokenDto $authorizationTokenDto): void
    {
        // TODO: Implement token() method.
    }
}
