<?php


use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\course\conversion\domain\dto\FalseUserActionsDto;
use app\models\dataObject\StaticClientDo;
use app\models\dataObject\StaticHitsDo;
use app\models\dataObject\StaticVisitDo;
use app\modules\v1\userAction\enum\UrlConvertEnum;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

class UrlConvertProcess
{

    /**
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public static function start(): void
    {
        /* @var Container $container */
        $container = Yii::$container;
        /* @var redisUtils $redisUtils */
        $redisUtils = $container->get(RedisUtils::class);
        do {
            $redisUrlConvertIpPop        = $redisUtils->getRedis()->rpop(UrlConvertEnum::REDIS_URL_CONVERT_IP);
            $redisUrlConvertIpList[]     = $redisUrlConvertIpPop;
            $redisUrlConvertClientPop    = $redisUtils->getRedis()->rpop(UrlConvertEnum::REDIS_URL_CONVERT_CLIENT);
            $redisUrlConvertClientList[] = $redisUrlConvertClientPop;
            $redisUrlConvertVisitPop     = $redisUtils->getRedis()->rpop(UrlConvertEnum::REDIS_URL_CONVERT_VISIT);
            $redisUrlConvertVisitList[]  = $redisUrlConvertVisitPop;
            //保存数据到备份队列中
            $redisUtils->getRedis()->rpush(UrlConvertEnum::REDIS_URL_CONVERT_IP_BACKUPS, [$redisUrlConvertIpPop]);
            $redisUtils->getRedis()->rpush(UrlConvertEnum::REDIS_URL_CONVERT_CLIENT_BACKUPS, [$redisUrlConvertClientPop]);
            $redisUtils->getRedis()->rpush(UrlConvertEnum::REDIS_URL_CONVERT_VISIT_BACKUPS, [$redisUrlConvertVisitPop]);
        } while ($redisUrlConvertIpPop && $redisUrlConvertClientPop && $redisUrlConvertVisitPop);

        try {
            /* @var ArrayUtils $arrayUtils */
            $arrayUtils = $container->get(ArrayUtils::class);
            /* @var CommandUrlConvertService $commandUrlConvertService */
            $commandUrlConvertService      = $container->get(CommandUrlConvertService::class);
            $staticClientDo                = new StaticClientDo();
            $staticVisitDo                 = new StaticVisitDo();
            $staticHitsDo                  = new StaticHitsDo();
            $conversionController          = new UrlConvertController($redisUtils, $arrayUtils, $commandUrlConvertService, $staticClientDo, $staticVisitDo, $staticHitsDo);
            $falseUrlConvertHitsDtoList    = $conversionController->addUrConvertHits($redisUrlConvertVisitList);
            $falseUrlConvertClientsDtoList = $conversionController->addUserConvertClient($redisUrlConvertVisitList);
            $falseUrlConvertVisitsDtoList  = $conversionController->addUserConvertVisit($redisUrlConvertVisitList);


            //成功时删除备份队列数据(注:不成功将保存该备份,该备份应由开发人员定期处理)
            for ($num = 1, $numMax = count($redisUrlConvertIpList); $num <= $numMax; $num++) {
                $redisUtils->getRedis()->rpop(UrlConvertEnum::REDIS_URL_CONVERT_IP_BACKUPS);
            }
            for ($num = 1, $numMax = count($redisUrlConvertClientList); $num <= $numMax; $num++) {
                $redisUtils->getRedis()->rpop(UrlConvertEnum::REDIS_URL_CONVERT_CLIENT_BACKUPS);
            }
            for ($num = 1, $numMax = count($redisUrlConvertVisitList); $num <= $numMax; $num++) {
                $redisUtils->getRedis()->rpop(UrlConvertEnum::REDIS_URL_CONVERT_VISIT_BACKUPS);
            }

            //插入失败的数据记录
            if ($falseUrlConvertHitsDtoList) {
                foreach ($falseUrlConvertHitsDtoList as $falseUserActionsDto) {
                    /* @var FalseUserActionsDto $falseUserActionsDto */
                    $falseUserActionsDto->userActionsDto = ArrayUtils::attributesAsMap($falseUserActionsDto->userActionsDto);
                    $redisUtils->getRedis()->rpush(UrlConvertEnum::REDIS_URL_CONVERT_IP_BACKUPS, [json_encode($falseUserActionsDto->attributes)]);
                }
            }
            //插入失败的数据记录
            if ($falseUrlConvertClientsDtoList) {
                foreach ($falseUrlConvertClientsDtoList as $falseUserActionsDto) {
                    /* @var FalseUserActionsDto $falseUserActionsDto */
                    $falseUserActionsDto->userActionsDto = ArrayUtils::attributesAsMap($falseUserActionsDto->userActionsDto);
                    $redisUtils->getRedis()->rpush(UrlConvertEnum::REDIS_URL_CONVERT_CLIENT_BACKUPS, [json_encode($falseUserActionsDto->attributes)]);
                }
            }
            //插入失败的数据记录
            if ($falseUrlConvertVisitsDtoList) {
                foreach ($falseUrlConvertVisitsDtoList as $falseUserActionsDto) {
                    /* @var FalseUserActionsDto $falseUserActionsDto */
                    $falseUserActionsDto->userActionsDto = ArrayUtils::attributesAsMap($falseUserActionsDto->userActionsDto);
                    $redisUtils->getRedis()->rpush(UrlConvertEnum::REDIS_URL_CONVERT_VISIT_BACKUPS, [json_encode($falseUserActionsDto->attributes)]);
                }
            }


        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            Yii::error($e->getMessage() . $e->getCode(), 'daemon');
        }

    }

}