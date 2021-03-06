<?php

namespace app\daemon\course\conversion\service\impl;

use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsRequestDto;
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
            //?????????????????????????????????
            $staticUrlFindList = $this->commandsStaticUrlService->findAll(['ident' => array_column($redisAddViewDtoList, 'token')]);
            $staticHitsFindList = $this->staticHits::find()->select(['ip', 'date', 'u_id']);
            //????????????:???????????????????????????,??????????????????
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
                //????????????
                $redisAddViewDto->u_id = $staticUrlFind->id;
                $redisAddViewDto->page = $staticUrlFind->url;
                if ($staticUrlFind->pcurl && !$redisAddViewDto->request_from_mobile) {
                    $redisAddViewDto->page = $staticUrlFind->pcurl;
                }
            }
            $staticHitsFindList = $staticHitsFindList->all();
            //????????????????????????
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
            //????????????
            $lastInsertId = $this->batchInsertUtils->onDuplicateKeyUpdate($redisAddViewDtoList, [
                'u_id',      //statis_url???id
                'ip',        //IP??????
                'country',   //??????
                'area',      //??????
                'date',      //??????
                'page',      //???
                'referer',   //??????
                'agent',     //?????????
                'createtime',//????????????
            ], $this->staticHits::tableName());
            if (!$lastInsertId) {
                throw new Exception('??????????????????!?????????id??????', [], 500);
            }
            $redisAddViewDtoList = array_unique($redisAddViewDtoList);
            //????????????????????????????????????
            $userActionsDtoList = [];
            foreach ($redisAddViewDtoList as $redisAddViewDto) {
                $userActionsDto = new UserActionsRequestDto();
                $userActionsDto->account_uin = $redisAddViewDto->account_uin;
                $userActionsDto->actions->user_action_set_id = $redisAddViewDto->user_action_set_id;
                $userActionsDto->actions->url = $redisAddViewDto->url;
                $userActionsDto->actions->action_time = time();
                $userActionsDto->actions->action_type = UserActionsTypeEnum::PAGE_VIEW;
                $userActionsDto->actions->trace->click_id = $redisAddViewDto->click_id;
                if ($redisAddViewDto->action_param) {
                    $userActionsDto->actions->action_param = $redisAddViewDto->action_param;
                }
                $userActionsDto->actions->outer_action_id = $lastInsertId;
                $userActionsDto->actions = [$userActionsDto->actions];
                $userActionsDtoList[] = $userActionsDto;
                $lastInsertId--;
            }
            $falseUserActionsDtoList = $this->userActionsApi->batchAdd($userActionsDtoList);
            //???????????????????????????
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
