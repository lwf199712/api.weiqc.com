<?php

namespace app\api\tencentMarketingApi\userActions\api;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\common\api\ApiBaseController;
use app\exception\TencentMarketingApiException;

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
    /* @var UserActionsDto */
    private $userActionsDto = UserActionsDto::class;

    /**
     * 上传用户行为数据
     *
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(): void
    {
        /* @var $userActionsDto */
        $userActionsDto = new $this->userActionsDto;
        /* @var $userActionsService UserActionsService */
        $userActionsService = new $this->userActionsService;
        $userActionsService->add($userActionsDto);
    }
}