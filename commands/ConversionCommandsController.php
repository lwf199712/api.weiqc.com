<?php

namespace app\commands;

use app\api\tencentMarketingApi\userActions\api\UserActionsAip;
use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\modules\v1\userAction\domain\po\StaticHitsPo;
use app\modules\v1\userAction\enum\ConversionEnum;
use app\utils\RedisUtils;
use yii\console\Controller;
use yii\db\Exception;

/**
 * Class ConversionCommandsController
 *
 * @property RedisUtils $redisUtils
 *
 * @package app\commands
 * @author: lirong
 */
class ConversionCommandsController extends Controller
{
    /* @var RedisUtils */
    protected $redisUtils;

    /**
     * ConversionCommandsController constructor.
     *
     * @param $id
     * @param $module
     * @param RedisUtils $redisUtils
     * @param array $config
     */
    public function __construct($id, $module, RedisUtils $redisUtils, $config = [])
    {
        $this->redisUtils = $redisUtils;
        parent::__construct($id, $module, $config);
    }

    /**
     * Landing page conversions - add views
     *
     * @author: lirong
     */
    public function actionAddViews(): void
    {
        $this->redisUtils->getRedis()->multi();
        try {
            //redis 导出数据库
            $staticHitsPoList = [];
            do {
                $staticHitsPo = $this->redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW);
                if ($staticHitsPo) {
                    $staticHitsPoList[] = unserialize($staticHitsPo, [StaticHitsPo::class]);
                }
                $this->redisUtils->getRedis()->discard();
                var_dump($staticHitsPoList);
                exit;
            } while ($staticHitsPo);
            //TODO 广点通用户行为点击数增加
            /* @var $userActionsAip UserActionsAip */

        } catch (Exception $e) {
        }
    }
}
