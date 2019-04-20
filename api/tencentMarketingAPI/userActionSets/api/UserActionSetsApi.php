<?php

namespace app\api\tencentMarketingApi\userActionSets\api;


use app\api\tencentMarketingApi\userActionSets\domain\dto\UserActionSetsAddRequestDto;
use app\api\tencentMarketingApi\userActionSets\domain\dto\UserActionSetsAddResponseDto;
use app\api\tencentMarketingApi\userActionSets\service\UserActionSetsService;
use app\common\api\ApiBaseController;
use app\common\exception\TencentMarketingApiException;

/**
 * 用户行为数据源
 * Class UserActionsApi
 *
 * @property UserActionSetsService $userActionSetsService
 * @package app\api\tencentMarketingApi\userActionSets\api
 * @author: lirong
 */
class UserActionSetsApi extends ApiBaseController
{
    /* @var UserActionSetsService */
    private $userActionSetsService;

    /**
     * UserActionSetsApi constructor.
     *
     * @param UserActionSetsService $userActionSetsService
     * @param array $config
     */
    public function __construct(UserActionSetsService $userActionSetsService, $config = [])
    {
        $this->userActionSetsService = $userActionSetsService;
        parent::__construct($config);
    }

    /**
     * 用户行为数据源 - 创建用户行为数据源
     *
     * @param string $accessToken
     * @param UserActionSetsAddRequestDto $userActionSetsAddRequestDto
     * @return UserActionSetsAddResponseDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(string $accessToken, UserActionSetsAddRequestDto $userActionSetsAddRequestDto): UserActionSetsAddResponseDto
    {
        return $this->userActionSetsService->add($accessToken, $userActionSetsAddRequestDto);
    }
}