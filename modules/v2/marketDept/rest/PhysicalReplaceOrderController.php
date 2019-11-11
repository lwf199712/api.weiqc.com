<?php
declare(strict_types=1);
namespace app\modules\v2\marketDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderDto;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderForm;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderImport;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderQuery;
use app\modules\v2\marketDept\service\PhysicalReplaceOrderService;
use Exception;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class PhysicalReplaceOrderController
 * @package app\modules\v2\marketDept\rest
 */
class PhysicalReplaceOrderController extends AdminBaseController
{
    /** @var PhysicalReplaceOrderQuery */
    public $physicalReplaceOrderQuery;
    /** @var PhysicalReplaceOrderService */
    public $physicalReplaceOrderService;
    /** @var PhysicalReplaceOrderImport */
    public $physicalReplaceOrderImport;
    /** @var PhysicalReplaceOrderForm */
    public $physicalReplaceOrderForm;
    /** @var PhysicalReplaceOrderDto */
    public $physicalReplaceOrderDto;

    public function __construct($id, $module,
                                PhysicalReplaceOrderQuery      $physicalReplaceOrderQuery,
                                PhysicalReplaceOrderService    $physicalReplaceOrderService,
                                PhysicalReplaceOrderImport     $physicalReplaceOrderImport,
                                PhysicalReplaceOrderForm       $physicalReplaceOrderForm,
                                PhysicalReplaceOrderDto        $physicalReplaceOrderDto,
                                $config = [])
    {
        $this->physicalReplaceOrderQuery    = $physicalReplaceOrderQuery;
        $this->physicalReplaceOrderService  = $physicalReplaceOrderService;
        $this->physicalReplaceOrderImport   = $physicalReplaceOrderImport;
        $this->physicalReplaceOrderForm     = $physicalReplaceOrderForm;
        $this->physicalReplaceOrderDto      = $physicalReplaceOrderDto;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'         => ['GET', 'HEAD', 'OPTIONS'],
            'export'        => ['GET', 'HEAD', 'OPTIONS'],
            'update'        => ['PUT', 'PATCH', 'OPTIONS'],
            'delete'        => ['DELETE', 'OPTIONS'],
            'import'        => ['POST', 'OPTIONS'],
            'audit'         => ['PUT', 'PATCH', 'OPTIONS'],
            'updateImport'  => ['POST', 'OPTIONS'],
            'updateStatus'  => ['POST', 'OPTIONS'],
        ];
    }

    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @throws HttpException
     * @author: weifeng
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
            case 'actionExport':
                return $this->physicalReplaceOrderQuery;
                break;
            case 'actionUpdateImport':
            case 'actionUpdateStatus':
            case 'actionImport':
                return $this->physicalReplaceOrderImport;
                break;
            case 'actionUpdate':
                return $this->physicalReplaceOrderForm;
                break;
            case 'actionAudit':
            case 'actionDelete':
                return $this->physicalReplaceOrderDto;
                break;
            default:
                throw new HttpException('UnKnow ActionName ');
        }
    }

    /**
     * 实物置换订单首页
     * @return array
     * @author weifeng
     */

    public function actionIndex()
    {
        try {
            $data = $this->physicalReplaceOrderService->listData($this->physicalReplaceOrderQuery);
            return ['返回数据成功', 200, $data];
        } catch (Exception $exception) {
            return ['返回数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 实物置换订单导入
     * @return array
     * @author weifeng
     */

    public function actionImport()
    {
        try {
            $data = $this->physicalReplaceOrderService->importReplaceOrder($this->physicalReplaceOrderImport);
            if (!$data) {
                throw new Exception('导入失败，请检查表格内容是否正确');
            }
            return ['导入数据成功', 200, $data];
        } catch (Exception $exception) {
            return ['导入数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 实物置换订单导出
     * @return array
     * @author weifeng
     */

    public function actionExport()
    {
        try {
            $data = $this->physicalReplaceOrderService->exportReplaceOrder($this->physicalReplaceOrderQuery);
            return ['导出数据成功', 200, $data['exportName']];
        } catch (Exception $exception) {
            return ['导出数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 实物置换订单编辑
     * @author weifeng
     */

    public function actionUpdate()
    {
        try {
            $data = $this->physicalReplaceOrderService->update($this->physicalReplaceOrderForm);
            return ['编辑数据成功', 200, $data];
        } catch (Exception $exception) {
            return ['编辑数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 实物置换订单删除
     * @return array
     * @author weifeng
     */

    public function actionDelete()
    {
        $num = $this->physicalReplaceOrderService->delete($this->physicalReplaceOrderDto);
        return ['删除成功', 200, $num];
    }

    /**
     * 实物置换订单审核
     * @author weifeng
     */
    public function actionAudit()
    {
        try {
            $res = $this->physicalReplaceOrderService->audit($this->physicalReplaceOrderDto);
            return ['审核成功', 200, $res];
        } catch (Exception $exception) {
            return ['审核失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 实物置换订单更新导入
     * @author weifeng
     */
    public function actionUpdateImport()
    {
        try {
            $data = $this->physicalReplaceOrderService->updateReplaceOrder($this->physicalReplaceOrderImport);
            if (!$data) {
                throw new Exception('导入失败，请检查表格内容是否正确');
            }
            return ['导入数据成功', 200, $data];
        } catch (Exception $exception) {
            return ['导入数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 更新寄出状态
     * @author weifeng
     */

    public function actionUpdateStatus()
    {
        try {
            $data = $this->physicalReplaceOrderService->updatePrizeSendStatus($this->physicalReplaceOrderImport);
            return ['更新寄出状态成功',200,$data];
        } catch (Exception $e) {
            return ['更新寄出状态失败', 500, $e->getMessage()];
        }
    }


}