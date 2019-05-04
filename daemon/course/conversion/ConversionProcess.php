<?php

namespace app\daemon\course\conversion;

use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\course\conversion\controller\ConversionController;
use app\daemon\course\conversion\domain\dto\FalseUserActionsDto;
use app\daemon\course\conversion\service\CourseStaticHitsService;
use app\modules\v1\userAction\enum\ConversionEnum;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

/**
 * Class ConversionEntrance
 *
 * @package app\daemon\course\conversion\controller
 * @author: lirong
 */
class ConversionProcess
{
    /**
     * 触发上报数量
     *
     * @var integer APPEAR_NUMBER
     * @author lirong
     */
    private const APPEAR_NUMBER = 1;

    /**
     * 定时器调用的静态方法
     *
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     * @author: lirong
     */
    public static function start(): void
    {
        static $redisPopList = [];
        do {
            /* @var Container $container */
            $container = Yii::$container;
            /* @var redisUtils $redisUtils */
            $redisUtils = $container->get(RedisUtils::class);
            //读取redis队列数据
            $redisAddViewPop = $redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW);
            if ($redisAddViewPop) {
                //保存数据到备份队列中
                $redisUtils->getRedis()->rpush(ConversionEnum::REDIS_ADD_VIEW_BACKUPS, [$redisAddViewPop]);
                $redisPopList[] = $redisAddViewPop;
                if (count($redisPopList) > self::APPEAR_NUMBER) {
                    try {
                        /* @var ArrayUtils $arrayUtils */
                        $arrayUtils = $container->get(ArrayUtils::class);
                        /* @var CourseStaticHitsService $commandsStaticHitsService */
                        $commandsStaticHitsService = $container->get(CourseStaticHitsService::class);
                        $conversionController = new ConversionController($redisUtils, $arrayUtils, $commandsStaticHitsService);
                        $falseUserActionsDtoList = $conversionController->actionAddViews($redisPopList);
                        //成功时删除备份队列数据(注:不成功将保存该备份,该备份应由开发人员定期处理)
                        for ($num = 1, $numMax = count($redisPopList); $num <= $numMax; $num++) {
                            $redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW_BACKUPS);
                        }
                        //插入失败的数据记录
                        if ($falseUserActionsDtoList) {
                            foreach ($falseUserActionsDtoList as $falseUserActionsDto) {
                                /* @var FalseUserActionsDto $falseUserActionsDto */
                                $falseUserActionsDto->userActionsDto = ArrayUtils::attributesAsMap($falseUserActionsDto->userActionsDto);
                                $redisUtils->getRedis()->rpush(ConversionEnum::REDIS_ADD_VIEW_BACKUPS, [json_encode($falseUserActionsDto->attributes)]);
                            }
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage()."\n";
                        Yii::error($e->getMessage() . $e->getCode(), 'daemon');
                    }
                    unset($conversionController);
                    $redisPopList = [];
                }
            }
        } while ($redisAddViewPop);
    }

}
