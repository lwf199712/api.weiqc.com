<?php
declare(strict_types=1);
namespace app\modules\v2\operateDept\service;

use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoQuery;

interface DesignCenterProviderInfoService
{
    /**
     * 设计中心-供应商信息列表
     * @param DesignCenterProviderInfoQuery $designCenterProviderInfoQuery
     * @return mixed
     * @author: weifeng
     */
    public function listInfo(DesignCenterProviderInfoQuery $designCenterProviderInfoQuery): array;

    /**
     * 设计中心-供应商信息添加
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     */
    public function createInfo(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool;

    /**
     * 设计中心-供应商信息编辑
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     */
    public function updateInfo(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool;

    /**
     * 设计中心-供应商信息删除
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return int
     */
    public function deleteInfo(DesignCenterProviderInfoForm $designCenterProviderInfoForm): int;

    /**
     * 设计中心-查询单条数据
     * @param $id
     * @return array
     */
    public function getInfo(int $id): array;
}