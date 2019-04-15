<?php

namespace app\commands;

use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\common\commands\CommandsBaseController;
use app\models\po\StaticHitsPo;
use app\modules\v1\userAction\enum\ConversionEnum;
use app\utils\ArrayUtils;
use app\utils\RedisUtils;
use yii\base\Module;

/**
 * Class ConversionCommandsController
 *
 * @property RedisUtils $redisUtils
 * @property ArrayUtils $arrayUtils
 * @property CommandsStaticHitsService $commandsStaticHitsService
 *
 * @package app\commands
 * @author: lirong
 */
class ConversionCommandsController extends CommandsBaseController
{
    /* @var RedisUtils */
    protected $redisUtils;
    /* @var ArrayUtils */
    protected $arrayUtils;
    /* @var CommandsStaticHitsService */
    protected $commandsStaticHitsService;

    /**
     * ConversionCommandsController constructor.
     *
     * @param int $id
     * @param Module $module
     * @param RedisUtils $redisUtils
     * @param ArrayUtils $arrayUtils
     * @param CommandsStaticHitsService $commandsStaticHitsService
     * @param array $config
     */
    public function __construct($id, $module,
                                RedisUtils $redisUtils,
                                ArrayUtils $arrayUtils,
                                CommandsStaticHitsService $commandsStaticHitsService,
                                $config = [])
    {
        $this->redisUtils = $redisUtils;
        $this->arrayUtils = $arrayUtils;
        $this->commandsStaticHitsService = $commandsStaticHitsService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Landing page conversions - add views
     *
     * @return array
     * @author: lirong
     */
    public function actionAddViews(): array
    {
        $staticHitsPoList = [];
        $staticHitsPoBase = new StaticHitsPo();
        do {
            $staticHitsPoPop = $this->redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW);
            if ($staticHitsPoPop) {
                $staticHitsPo = clone $staticHitsPoBase;
                $staticHitsPo->attributes = json_decode($staticHitsPoPop, true);
                $staticHitsPoList[] = $staticHitsPo;
                $staticHitsPoList = $this->arrayUtils->uniqueArrayDelete($staticHitsPoList, ['ip', 'date', 'u_id']);
            }
        } while ($staticHitsPoPop);
        //广点通用户行为点击数增加
        $this->commandsStaticHitsService->batchInsert($staticHitsPoList);
        return [true];
    }
}
