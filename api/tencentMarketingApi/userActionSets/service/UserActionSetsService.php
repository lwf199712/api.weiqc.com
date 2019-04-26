<?php

namespace app\api\tencentMarketingApi\userActionSets\service;

use app\api\tencentMarketingApi\userActionSets\domain\dto\UserActionSetsAddRequestDto;
use app\api\tencentMarketingApi\userActionSets\domain\dto\UserActionSetsAddResponseDto;
use app\common\exception\TencentMarketingApiException;

/**
 * Interface UserActionSetsService
 *
 * @package app\modules\v1\conversion\service
 * @author: lirong
 */
interface UserActionSetsService
{
    /**
     * 上传用户行为数据
     *
     * @param string $accessToken
     * @param UserActionSetsAddRequestDto $userActionSetsAddRequestDto
     * @return UserActionSetsAddResponseDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(string $accessToken,UserActionSetsAddRequestDto $userActionSetsAddRequestDto): UserActionSetsAddResponseDto;
}
