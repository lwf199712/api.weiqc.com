<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterProviderInfoQuery;
use app\modules\v2\operateDept\service\DesignCenterProviderInfoService;
use Exception;
use yii\base\Model;


class DesignCenterProviderInfoController extends AdminBaseController
{
    /** @var DesignCenterProviderInfoService */
    public $designCenterProviderInfoService;
    /** @var DesignCenterProviderInfoQuery */
    public $designCenterProviderInfoQuery;
    /** @var DesignCenterProviderInfoForm */
    public $designCenterProviderInfoForm;

    public function __construct($id, $module,
                                DesignCenterProviderInfoService $designCenterProviderInfoService,
                                DesignCenterProviderInfoQuery   $designCenterProviderInfoQuery,
                                DesignCenterProviderInfoForm    $designCenterProviderInfoForm,
                                $config = [])
    {
        $this->designCenterProviderInfoService = $designCenterProviderInfoService;
        $this->designCenterProviderInfoQuery   = $designCenterProviderInfoQuery;
        $this->designCenterProviderInfoForm    = $designCenterProviderInfoForm;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'  => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
            'info'   => ['GET', 'HEAD', 'OPTIONS'],
        ];
    }

    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @throws Exception
     * @author: weifeng
     */
    public function dtoMap(string $actionName): Model
    {
        return [
            'actionIndex'  => $this->designCenterProviderInfoQuery,
            'actionCreate' => $this->designCenterProviderInfoForm,
            'actionUpdate' => $this->designCenterProviderInfoForm,
            'actionDelete' => $this->designCenterProviderInfoForm,
            'actionInfo'   => $this->designCenterProviderInfoForm,
        ][$actionName];
    }

    /**
     * 设计中心供应商信息-首页
     * @return array|mixed
     * @author: weifeng
     */
    public function actionIndex(): ?array
    {
        return ['成功返回数据', 200, $this->designCenterProviderInfoService->listInfo($this->designCenterProviderInfoQuery)];
    }

    /**
     * 设计中心供应商信息-添加
     * @return array|mixed
     * @author: weifeng
     */
    public function actionCreate(): ?array
    {
        return ['新增成功', 200, $this->designCenterProviderInfoService->createInfo($this->designCenterProviderInfoForm)];
    }

    /**
     * 设计中心供应商信息-更新
     * @return array
     * @author: weifeng
     */
    public function actionUpdate(): ?array
    {
        return ['修改成功', 200, $this->designCenterProviderInfoService->updateInfo($this->designCenterProviderInfoForm)];
    }

    /**
     * 设计中心供应商信息-删除
     * @return array
     * @author: weifeng
     */
    public function actionDelete(): ?array
    {
        return ['删除成功', 200, $this->designCenterProviderInfoService->deleteInfo($this->designCenterProviderInfoForm)];
    }

    /**
     * 设计中心供应商信息-单条查询
     */

    public function actionInfo(): array
    {
        return ['返回成功', 200, $this->designCenterProviderInfoService->getInfo((int)$this->designCenterProviderInfoForm->id)];
    }
}