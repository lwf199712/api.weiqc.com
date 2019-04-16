<?php

namespace app\commands\conversionCommands\service\impl;

use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingApi\userActions\enum\ActionTypeEnum;
use app\commands\conversionCommands\domain\dto\RedisAddViewDto;
use app\commands\conversionCommands\service\CommandsStaticConversionService;
use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\commands\utils\CommandsBatchInsertUtils;
use app\common\exception\TencentMarketingApiException;
use app\models\po\StaticConversionPo;
use app\models\po\StaticHitsPo;
use app\utils\ArrayUtils;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Interface ConversionService
 *
 * @property StaticHitsPo $staticHits
 * @property CommandsBatchInsertUtils $batchInsertUtils
 * @property ArrayUtils $arrayUtils
 * @property UserActionsApi $userActionsApi
 * @property CommandsStaticConversionService $commandsStaticConversionService
 * @author: lirong
 */
class CommandsCommandsStaticHitsImpl extends BaseObject implements CommandsStaticHitsService
{
    /* @var UserActionsApi */
    protected $userActionsApi;
    /* @var CommandsStaticConversionService */
    protected $commandsStaticConversionService;
    /* @var StaticHitsPo */
    private $staticHits;
    /* @var CommandsBatchInsertUtils */
    private $batchInsertUtils;
    /* @var ArrayUtils */
    private $arrayUtils;

    /**
     * UserActionUserActionStaticServiceConversionsImpl constructor.
     *
     * @param StaticHitsPo $staticHits
     * @param CommandsBatchInsertUtils $batchInsertUtils
     * @param ArrayUtils $arrayUtils
     * @param UserActionsApi $userActionsApi
     * @param CommandsStaticConversionService $commandsStaticConversionService
     * @param array $config
     */
    public function __construct(StaticHitsPo $staticHits,
                                CommandsBatchInsertUtils $batchInsertUtils,
                                ArrayUtils $arrayUtils,
                                userActionsApi $userActionsApi,
                                CommandsStaticConversionService $commandsStaticConversionService,
                                $config = [])
    {
        $this->staticHits = $staticHits;
        $this->batchInsertUtils = $batchInsertUtils;
        $this->arrayUtils = $arrayUtils;
        $this->userActionsApi = $userActionsApi;
        $this->commandsStaticConversionService = $commandsStaticConversionService;
        parent::__construct($config);
    }

    /**
     * batch insert
     *
     * @param array $redisAddViewDtoList
     * @return void
     * @author: lirong
     */
    public function batchInsert(array $redisAddViewDtoList): void
    {
        if ($redisAddViewDtoList) {
            //查询数据是否已经被记录
            $staticConversionFindList = $this->commandsStaticConversionService->findAll(['token' => array_column($redisAddViewDtoList, 'token')]);
            $staticHitsFindList = $this->staticHits::find()->select(['ip', 'date', 'u_id']);
            foreach ($redisAddViewDtoList as $key => $redisAddViewDto) {
                /* @var $redisAddViewDto RedisAddViewDto */
                /* @var $staticConversionFind StaticConversionPo */
                $staticConversionFind = $this->arrayUtils->findOne($staticConversionFindList, ['token' => $redisAddViewDto->token]);
                if (!$staticConversionFind) {
                    unset($redisAddViewDtoList[$key]);
                }
                $redisAddViewDto->u_id = $staticConversionFind->u_id;
                $staticHitsFindList = $staticHitsFindList->orWhere([
                    'ip'   => $redisAddViewDto->ip,
                    'date' => $redisAddViewDto->date,
                    'u_id' => $redisAddViewDto->u_id,
                ]);
            }
            $staticHitsFindList = $staticHitsFindList->all();
            foreach ($redisAddViewDtoList as $key => $redisAddViewDto) {
                /* @var $staticHitsFind StaticHitsPo */
                if ($this->arrayUtils->arrayExists($staticHitsFindList, [
                    'ip'   => $redisAddViewDto->ip,
                    'date' => $redisAddViewDto->date,
                    'u_id' => $redisAddViewDto->u_id
                ])) {
                    unset($redisAddViewDtoList[$key]);
                }
            }
            //批量插入
            $redisAddViewDtoList = array_values($redisAddViewDtoList);
            $redisAddViewDtoChunkList = array_chunk($redisAddViewDtoList, 1000);
            try {
                foreach ($redisAddViewDtoChunkList as $key => $redisAddViewDtoList) {
                    $lastInsertId = $this->batchInsertUtils->onDuplicateKeyUpdate($redisAddViewDtoList, [
                        'u_id',      //statis_url表id
                        'ip',        //IP地址
                        'country',   //国家
                        'area',      //区域
                        'date',      //日期
                        'page',      //页
                        'referer',   //引荐
                        'agent',     //代理人
                        'createtime',//创建时间
                    ], $this->staticHits::tableName());
                    if (!$lastInsertId) {
                        throw new Exception('批量插入失败!返回的id为空', [], 500);
                    }
                    //广点通用户行为点击数增加
                    foreach ($redisAddViewDtoList as $redisAddViewDto) {
                        $userActionsDto = new UserActionsDto();
                        $userActionsDto->account_id = $redisAddViewDto->account_id;
                        $userActionsDto->actions = new ActionsDto();
                        $userActionsDto->actions->user_action_set_id = $redisAddViewDto->user_action_set_id;
                        $userActionsDto->actions->url = $redisAddViewDto->url;
                        $userActionsDto->actions->action_time = time();
                        $userActionsDto->actions->action_type = ActionTypeEnum::PAGE_VIEW;
                        $userActionsDto->actions->trace = new TraceDto();
                        $userActionsDto->actions->trace->click_id = $redisAddViewDto->click_id;
                        if ($redisAddViewDto->action_param) {
                            $userActionsDto->actions->action_param = $redisAddViewDto->action_param;
                        }
                        $userActionsDto->actions->outer_action_id = $lastInsertId;
                        $userActionsDto->actions = [$userActionsDto->actions];
                        $this->userActionsApi->add($userActionsDto);
                        $lastInsertId--;
                    }
                    unset($redisAddViewDtoChunkList[$key]);
                }
            } catch (Exception|TencentMarketingApiException $e) {

            }
        }
    }
}
