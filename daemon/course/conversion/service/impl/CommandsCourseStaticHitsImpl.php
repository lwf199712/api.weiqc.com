<?php

namespace app\daemon\course\conversion\service\impl;

use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsActionsRequestDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsRequestDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsTraceRequestDto;
use app\api\tencentMarketingApi\userActions\enum\UserActionsTypeEnum;
use app\daemon\common\utils\CommandsBatchInsertUtils;
use app\daemon\course\conversion\domain\dto\FalseUserActionsDto;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;
use app\daemon\course\conversion\service\CourseStaticHitsService;
use app\daemon\course\conversion\service\CommandsStaticUrlService;
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
class CommandsCourseStaticHitsImpl extends BaseObject implements CourseStaticHitsService
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
     * @param CommandsStaticUrlService $commandsStaticUrlApi
     * @param array $config
     */
    public function __construct(StaticHitsDo $staticHits,
                                CommandsBatchInsertUtils $batchInsertUtils,
                                ArrayUtils $arrayUtils,
                                UserActionsApi $userActionsApi,
                                CommandsStaticUrlService $commandsStaticUrlApi,
                                $config = [])
    {
        $this->staticHits = $staticHits;
        $this->batchInsertUtils = $batchInsertUtils;
        $this->arrayUtils = $arrayUtils;
        $this->userActionsApi = $userActionsApi;
        $this->commandsStaticUrlService = $commandsStaticUrlApi;
        parent::__construct($config);
    }

    /**
     * batch insert
     *
     * @param array $redisAddViewDtoList
     * @return array
     * @throws Exception
     * @author: lirong
     */
    public function batchInsert(array $redisAddViewDtoList): array
    {
        if ($redisAddViewDtoList) {
            //查询数据是否已经被记录
            $staticUrlFindList = $this->commandsStaticUrlService->findAll(['ident' => array_column($redisAddViewDtoList, 'token')]);
            $staticHitsFindList = $this->staticHits::find()->select(['ip', 'date', 'u_id']);
            //耦合代码:执行查询落地页同时,进行数据赋值
            foreach ($redisAddViewDtoList as $key => $redisAddViewDto) {
                /* @var $redisAddViewDto RedisAddViewDto */
                /* @var $staticUrlFind StaticUrlDo */
                $staticUrlFind = $this->arrayUtils->findOne($staticUrlFindList, ['ident' => $redisAddViewDto->token]);
                if (!$staticUrlFind) {
                    unset($redisAddViewDtoList[$key]);
                }
                $staticHitsFindList = $staticHitsFindList->orWhere([
                    'ip'   => $redisAddViewDto->ip,
                    'date' => $redisAddViewDto->date,
                    'u_id' => $staticUrlFind->id
                ]);
                //数据赋值
                $redisAddViewDto->u_id = $staticUrlFind->id;
                $redisAddViewDto->page = $staticUrlFind->url;
                if ($staticUrlFind->pcurl && !$redisAddViewDto->request_from_mobile) {
                    $redisAddViewDto->page = $staticUrlFind->pcurl;
                }
            }
            $staticHitsFindList = $staticHitsFindList->all();
            //检查数据是否存在
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
            $userActionsDtoList = [];
            foreach ($redisAddViewDtoList as $redisAddViewDto) {
                $userActionsDto = new UserActionsRequestDto();
                $userActionsDto->account_id = $redisAddViewDto->account_id;
                $userActionsDto->actions = new UserActionsActionsRequestDto();
                $userActionsDto->actions->user_action_set_id = $redisAddViewDto->user_action_set_id;
                $userActionsDto->actions->url = $redisAddViewDto->url;
                $userActionsDto->actions->action_time = time();
                $userActionsDto->actions->action_type = UserActionsTypeEnum::PAGE_VIEW;
                $userActionsDto->actions->trace = new UserActionsTraceRequestDto();
                $userActionsDto->actions->trace->click_id = $redisAddViewDto->click_id;
                if ($redisAddViewDto->action_param) {
                    $userActionsDto->actions->action_param = $redisAddViewDto->action_param;
                }
                $userActionsDto->actions->outer_action_id = $lastInsertId;
                $userActionsDto->actions = [$userActionsDto->actions];
                $userActionsDtoList[] = $userActionsDto;
                $lastInsertId--;
            }
            //删除上报失败的记录
            $falseUserActionsDtoList = $this->userActionsApi->batchAdd($userActionsDtoList);
            if ($falseUserActionsDtoList) {
                $deleteList = [];
                foreach ($falseUserActionsDtoList as $falseUserActionsDto) {
                    /* @var FalseUserActionsDto $falseUserActionsDto */
                    $deleteList[] = $falseUserActionsDto->userActionsDto->actions->outer_action_id;
                }
                $this->staticHits::deleteAll(['id' => $deleteList]);
            }
            return $falseUserActionsDtoList;
        }
        return [];
    }
}
