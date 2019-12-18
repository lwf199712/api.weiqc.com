<?php
declare(strict_types=1);
namespace app\modules\v2\operateDept\service\impl;

use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoQuery;
use app\modules\v2\operateDept\domain\repository\DesignCenterProviderInfoDoManager;
use app\modules\v2\operateDept\service\DesignCenterProviderInfoService;
use app\modules\v2\operateDept\domain\entity\DesignCenterProviderInfoEntity;
use Exception;
use RuntimeException;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

class DesignCenterProviderInfoImpl extends BaseObject implements DesignCenterProviderInfoService
{
    /** @var DesignCenterProviderInfoDoManager */
    public $designCenterProviderInfoDoManager;
    /** @var DesignCenterProviderInfoQuery */
    public $designCenterProviderInfoQuery;
    /** @var ActiveRecord */
    public $model;

    public function __construct(
        DesignCenterProviderInfoDoManager $designCenterProviderInfoDoManager,
        DesignCenterProviderInfoQuery     $designCenterProviderInfoQuery,
        DesignCenterProviderInfoEntity    $designCenterProviderInfoEntity,
                                          $config = [])
    {
        $this->model                             = $designCenterProviderInfoEntity;
        $this->designCenterProviderInfoDoManager = $designCenterProviderInfoDoManager;
        $this->designCenterProviderInfoQuery     = $designCenterProviderInfoQuery;
        parent::__construct($config);
    }

    /**
     * 设计中心供应商信息-列表
     * @param DesignCenterProviderInfoQuery $designCenterProviderInfoQuery
     * @return array
     * @author: weifeng
     */
    public function listInfo(DesignCenterProviderInfoQuery $designCenterProviderInfoQuery): array
    {
        //首页数据
        $list['lists']      = $this->designCenterProviderInfoDoManager->listDataProvider($designCenterProviderInfoQuery)->getModels();
        $list['totalCount'] = $this->designCenterProviderInfoDoManager->listDataProvider($designCenterProviderInfoQuery)->getTotalCount();
        return $list;
    }

    /**
     * 设计中心供应商信息-添加
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     * @throws Exception
     * @author: weifeng
     */
    public function createInfo(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool
    {
        $result = $this->model->createEntity($designCenterProviderInfoForm);
        if ($result === false) {
            throw new RuntimeException('新增设计中心供应商信息失败');
        }
        return $result;
    }

    /**
     * 设计中心供应商信息-编辑
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return bool
     * @author: weifeng
     */
    public function updateInfo(DesignCenterProviderInfoForm $designCenterProviderInfoForm): bool
    {
        $result = $this->model->updateEntity($designCenterProviderInfoForm);
        if ($result === false) {
            throw new RuntimeException('编辑设计中心供应商信息失败');
        }
        return $result;
    }

    /**
     * 设计中心供应商信息-删除
     * @param DesignCenterProviderInfoForm $designCenterProviderInfoForm
     * @return int
     * @author: weifeng
     */
    public function deleteInfo(DesignCenterProviderInfoForm $designCenterProviderInfoForm): int
    {
        $result = $this->model->deleteEntity($designCenterProviderInfoForm);
        if ($result === false) {
            throw new RuntimeException('删除设计中心供应商信息失败');
        }
        return $result;
    }
}