<?php

namespace app\commands;

use app\api\tencentMarketingApi\userActions\api\UserActionsAip;
use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\utils\RedisUtils;
use yii\console\Controller;

/**
 * Class ConversionCommands
 *
 * @property RedisUtils $redisUtils
 * @package app\commands
 * @author: lirong
 */
class ConversionCommands extends Controller
{
    /* @var RedisUtils */
    protected $redisUtils;

    /**
     * ConversionCommands constructor.
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
        //TODO redis 写入数据库

        //TODO 广点通用户行为点击数增加
        $userActionsDto = new UserActionsDto();
        $userActionsDto->account_id = $this->request->post('account_id', -1);
        $userActionsDto->actions = new ActionsDto();
        $userActionsDto->actions->user_action_set_id = $this->request->post('user_action_set_id');
        $userActionsDto->actions->url = $this->request->post('url');
        $userActionsDto->actions->action_time = time();
        $userActionsDto->actions->action_type = ActionsDto::VIEW_CONTENT;
        $userActionsDto->actions->trace = new TraceDto();
        $userActionsDto->actions->trace->click_id = $this->request->post('click_id', -1);
        if ($this->request->post('action_param')) {
            $userActionsDto->actions->action_param = $this->request->post('action_param');
        }
        $userActionsDto->actions->outer_action_id = $staticConversionId;
        $userActionsDto->actions = [$userActionsDto->actions];
        /* @var $userActionsAip UserActionsAip */
        $userActionsAip = new $this->userActionsController;
        $userActionsAip->add($userActionsDto);
    }
}
