<?php

namespace app\api\tencentMarketingApi\userActions\api;

use app\api\tencentMarketingApi\userActions\domain\dto\ActionsDto;
use app\api\tencentMarketingApi\userActions\domain\dto\TraceDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\common\api\ApiBaseController;
use app\common\exception\TencentMarketingApiException;
use Exception;

/**
 * 用户行为数据 (User Action)
 * Class UserActionsController
 *
 * @property UserActionsService $userActionsService
 * @property UserActionsDto $userActionsDto
 * @package app\modules\v1\conversion\rest
 * @author: lirong
 */
class UserActionsAip extends ApiBaseController
{
    /* @var UserActionsService */
    private $userActionsService = UserActionsImpl::class;

    /**
     * 上传用户行为数据
     *
     * @throws TencentMarketingApiException
     * @throws Exception
     * @author: lirong
     */
    public function add(): void
    {
        $userActionsDto = new UserActionsDto;
        $userActionsDto->account_id = $this->request->post('account_id', -1);
        $userActionsDto->actions = new ActionsDto;
        $userActionsDto->actions->user_action_set_id = $this->request->post('user_action_set_id');
        $userActionsDto->actions->url = $this->request->post('url');
        $userActionsDto->actions->action_time = time();
        $userActionsDto->actions->action_type = ActionsDto::COMPLETE_ORDER;
        $userActionsDto->actions->trace = new TraceDto;
        $userActionsDto->actions->trace->click_id = $this->request->post('click_id', -1);
        if ($this->request->post('action_param')) {
            $userActionsDto->actions->action_param = $this->request->post('action_param');
        }
        $userActionsDto->actions->outer_action_id = $this->request->post('outer_action_id', random_int(1, 9999) . time());
        $userActionsDto->actions = [$userActionsDto->actions];
        /* @var $userActionsService UserActionsService */
        $userActionsService = new $this->userActionsService;
        $userActionsService->add($userActionsDto);
    }
}