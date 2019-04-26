<?php

namespace app\api\tencentMarketingApi\userActions\api;

use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsActionsRequestDto;
use app\api\tencentMarketingApi\userActions\domain\dto\UserActionsRequestDto;
use app\api\tencentMarketingApi\userActions\service\UserActionsService;
use app\common\api\ApiBaseController;
use app\common\exception\TencentMarketingApiException;

/**
 * 用户行为数据 (User Action)
 * Class UserActionsController
 *
 * @property UserActionsService $userActionsService
 * @property UserActionsActionsRequestDto $userActionsDto
 * @package app\modules\v1\conversion\rest
 * @author: lirong
 */
class UserActionsApi extends ApiBaseController
{
    /* @var UserActionsService */
    private $userActionsService;

    /**
     * UserActionsAip constructor.
     *
     * @param UserActionsService $userActionsService
     * @param array $config
     */
    public function __construct(UserActionsService $userActionsService, $config = [])
    {
        $this->userActionsService = $userActionsService;
        parent::__construct($config);
    }

    /**
     * 用户行为数据API - 上传用户行为数据
     *
     * @param UserActionsRequestDto $userActionsDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function add(UserActionsRequestDto $userActionsDto): void
    {
        $this->userActionsService->add($userActionsDto);
    }

    /**
     * 用户行为数据API - 批量上报用户行为
     *
     * @param array $userActionsDtoList
     * @return array
     * @author: lirong
     */
    public function batchAdd(array $userActionsDtoList): array
    {
        if ($userActionsDtoList) {
            return $this->userActionsService->batchAdd($userActionsDtoList);
        }
        return [];
    }
}