<?php

namespace app\daemon\conversionCommands\service\impl;

use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingApi\userActions\enum\ActionTypeEnum;
use app\commands\utils\CommandsBatchInsertUtils;
use app\common\exception\TencentMarketingApiException;
use app\daemon\conversionCommands\domain\dto\RedisAddViewDto;
use app\daemon\conversionCommands\service\CommandsStaticHitsService;
use app\daemon\conversionCommands\service\CommandsStaticUrlService;
use app\models\dataObject\StaticHitsDo;
use app\models\dataObject\StaticUrlDo;
use app\common\utils\ArrayUtils;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Interface ConversionService
 *
 * @property StaticHitsDo $staticHits
 * @property CommandsBatchInsertUtils $batchInsertUtils
 * @property ArrayUtils $arrayUtils
 * @property UserActionsApi $userActionsApi
 * @property CommandsStaticUrlService $commandsStaticUrlService
 * @author: lirong
 */
class CommandsCommandsStaticHitsImpl extends BaseObject implements CommandsStaticHitsService
{
    /* @var UserActionsApi */
    protected $userActionsApi;
    /* @var CommandsStaticUrlService */
    protected $commandsStaticUrlService;
    /* @var StaticHitsDo */
    protected $staticHits;
    /* @var CommandsBatchInsertUtils */
    protected $batchInsertUtils;
    /* @var ArrayUtils */
    protected $arrayUtils;

    /**
     * UserActionUserActionStaticServiceConversionsImpl constructor.
     *
     * @param StaticHitsDo $staticHits
     * @param CommandsBatchInsertUtils $batchInsertUtils
     * @param ArrayUtils $arrayUtils
     * @param UserActionsApi $userActionsApi
     * @param CommandsStaticUrlService $commandsStaticUrlService
     * @param array $config
     */
    public function __construct(StaticHitsDo $staticHits,
                                CommandsBatchInsertUtils $batchInsertUtils,
                                ArrayUtils $arrayUtils,
                                UserActionsApi $userActionsApi,
                                CommandsStaticUrlService $commandsStaticUrlService,
                                $config = [])
    {
        $this->staticHits = $staticHits;
        $this->batchInsertUtils = $batchInsertUtils;
        $this->arrayUtils = $arrayUtils;
        $this->userActionsApi = $userActionsApi;
        $this->commandsStaticUrlService = $commandsStaticUrlService;
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
            $staticUrlFindList = $this->commandsStaticUrlService->findAll(['ident' => array_column($redisAddViewDtoList, 'token')]);
            $staticHitsFindList = $this->staticHits::find()->select(['ip', 'date', 'u_id']);
            foreach ($redisAddViewDtoList as $key => $redisAddViewDto) {
                /* @var $redisAddViewDto RedisAddViewDto */
                /* @var $staticUrlFind StaticUrlDo */
                $staticUrlFind = $this->arrayUtils->findOne($staticUrlFindList, ['ident' => $redisAddViewDto->token]);
                if (!$staticUrlFind) {
                    unset($redisAddViewDtoList[$key]);
                }
                $redisAddViewDto->u_id = $staticUrlFind->id;
                $staticHitsFindList = $staticHitsFindList->orWhere([
                    'ip'   => $redisAddViewDto->ip,
                    'date' => $redisAddViewDto->date,
                    'u_id' => $redisAddViewDto->u_id,
                ]);
            }
            $staticHitsFindList = $staticHitsFindList->all();
            foreach ($redisAddViewDtoList as $key => $redisAddViewDto) {
                /* @var $staticHitsFind StaticHitsDo */
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
            $redisAddViewDtoChunkList = array_chunk($redisAddViewDtoList, 500);
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
                    $redisAddViewDtoList = array_unique($redisAddViewDtoList);
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
                var_dump($e->getMessage());
                exit;
            }
        }
    }
}
