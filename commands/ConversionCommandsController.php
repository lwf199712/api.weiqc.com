<?php

namespace app\commands;

use app\commands\conversionCommands\domain\dto\RedisAddViewDto;
use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\common\commands\CommandsBaseController;
use app\common\exception\TencentMarketingApiException;
use app\modules\v1\userAction\enum\ConversionEnum;
use app\utils\ArrayUtils;
use app\utils\RedisUtils;
use yii\base\Module;

/**
 * Class ConversionCommandsTest
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
     * ConversionCommandsTest constructor.
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
        try {
            $redisAddViewDtoList = [];
            $redisAddViewBaseDto = new RedisAddViewDto();
            do {
                $redisAddViewPop = $this->redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW);
                if ($redisAddViewPop) {
                    $redisAddViewDto = clone $redisAddViewBaseDto;
                    $redisAddViewDto->attributes = json_decode($redisAddViewPop, true);
                    $redisAddViewDtoList[] = $redisAddViewDto;
                }
            } while ($redisAddViewPop);
            $redisAddViewDtoList = $this->arrayUtils->uniqueArrayDelete($redisAddViewDtoList, ['ip', 'date', 'u_id']);

            $this->commandsStaticHitsService->batchInsert($redisAddViewDtoList);
            return [true];
        } catch (TencentMarketingApiException $e) {
            return [false];
        }
    }

}
