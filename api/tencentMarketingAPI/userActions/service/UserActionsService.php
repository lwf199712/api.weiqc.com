<?php

namespace app\api\tencentMarketingAPI\userActions\service;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsRequestDto;
use app\common\exception\TencentMarketingApiException;

/**
 * Interface UserActionSetsService
 *
 * @package app\modules\v1\conversion\service
 * @author: lirong
 */
interface UserActionsService
{
    /**
     * 上传用户行为数据
     *
     * @param UserActionsRequestDto $userActionsRequestDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(UserActionsRequestDto $userActionsRequestDto): void;

    /**
     * 批量上传用户行为数据
     *
     * @param array $userActionsDtoList
     * @return array
     * @author: lirong
     */
    public function batchAdd(array $userActionsDtoList):array;
}
