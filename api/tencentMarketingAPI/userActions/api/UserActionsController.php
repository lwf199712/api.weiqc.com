<?php

namespace app\api\tencentMarketingApi\userActions\api;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\exception\TencentMarketingApiException;

/**
 * 用户行为数据 (User Action)
 * Class UserActionsController
 *
 * @package app\modules\v1\conversion\rest
 * @author: lirong
 */
class UserActionsController
{
    /* @var UserActionsService */
    private static $userActionsService = UserActionsImpl::class;
    /* @var UserActionsDto */
    private static $userActionsDto = UserActionsDto::class;

    /**
     * 上传用户行为数据
     *
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public static function add(): void
    {
        /* @var $userActionsDto */
        $userActionsDto = new self::$userActionsDto;
        /* @var $userActionsService UserActionsService */
        $userActionsService = new self::$userActionsService;
        $userActionsService->add($userActionsDto);
    }
}