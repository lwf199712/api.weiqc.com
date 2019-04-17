<?php

namespace app\daemon\conversionCommands\controller;

use app\common\commands\CommandsBaseController;
use app\common\exception\TencentMarketingApiException;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\conversionCommands\domain\dto\RedisAddViewDto;
use app\daemon\conversionCommands\service\CommandsStaticHitsService;
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
//                $redisAddViewPop = $this->redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW);
                //TODO 测试
                $redisAddViewPop = '{"token":"55909e3af1a55","u_id":null,"ip":"127.0.0.1","country":"\u672c\u673a\u5730\u5740","area":"","date":1555372800,"referer":null,"agent":"PostmanRuntime\/7.6.1","createtime":1555406004,"account_id":"1435","user_action_set_id":"665481","click_id":"11111","action_param":[],"url":"http:\/\/api.weiqc.com\/v1\/conversion\/rest\/add-conversion2"}';
                if ($redisAddViewPop) {
                    $redisAddViewDto = clone $redisAddViewBaseDto;
                    $redisAddViewDto->attributes = json_decode($redisAddViewPop, true);
                    $redisAddViewDtoList[] = $redisAddViewDto;
                }
                $redisAddViewPop = false;
            } while ($redisAddViewPop);
            $redisAddViewDtoList = $this->arrayUtils->uniqueArrayDelete($redisAddViewDtoList, ['ip', 'date', 'u_id']);

            $this->commandsStaticHitsService->batchInsert($redisAddViewDtoList);
            return [true, '操作成功!'];
        } catch (TencentMarketingApiException $e) {
            return [false, '操作失败' . $e->getMessage()];
        }
    }

}
