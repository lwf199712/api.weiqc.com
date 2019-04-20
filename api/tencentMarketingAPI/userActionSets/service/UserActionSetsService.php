<?php

namespace app\api\tencentMarketingAPI\userActions\service;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsDto;
use app\common\exception\TencentMarketingApiException;

/**
 * Interface UserActionsService
 *
 * @package app\modules\v1\conversion\service
 * @author: lirong
 */
interface UserActionsService
{
    /**
     * 上传用户行为数据
     *
     * @param UserActionsDto $userActionsDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(UserActionsDto $userActionsDto): void;

}
