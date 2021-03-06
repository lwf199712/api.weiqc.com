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
     * ????????????
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
     * ????????????????????????
     * @return array
     * @author weifeng
     */

    public function actionIndex(): ?array
    {
        try {
            $data = $this->physicalReplaceOrderService->listData($this->physicalReplaceOrderQuery);
            return ['??????????????????', 200, $data];
        } catch (Exception $exception) {
            return ['??????????????????', 500, $exception->getMessage()];
        }
    }

    /**
     * ????????????????????????
     * @return array
     * @author weifeng
     */

    public function actionImport(): ?array
    {
        try {
            $data = $this->physicalReplaceOrderService->importReplaceOrder($this->physicalReplaceOrderImport);
            if (!$data) {
                throw new Exception('????????????????????????????????????????????????');
            }
            return ['??????????????????', 200, $data];
        } catch (Exception $exception) {
            return ['??????????????????', 500, $exception->getMessage()];
        }
    }

    /**
     * ????????????????????????
     * @return array
     * @author weifeng
     */

    public function actionExport(): ?array
    {
        try {
            $data = $this->physicalReplaceOrderService->exportReplaceOrder($this->physicalReplaceOrderQuery);
            return ['??????????????????', 200, $data['exportName']];
        } catch (Exception $exception) {
            return ['??????????????????', 500, $exception->getMessage()];
        }
    }

    /**
     * ????????????????????????
     * @author weifeng
     */

    public function actionUpdate(): ?array
    {
        try {
            $data = $this->physicalReplaceOrderService->update($this->physicalReplaceOrderForm);
            return ['??????????????????', 200, $data];
        } catch (Exception $exception) {
            return ['??????????????????', 500, $exception->getMessage()];
        }
    }

    /**
     * ????????????????????????
     * @return array
     * @author weifeng
     */

    public function actionDelete(): array
    {
        $num = $this->physicalReplaceOrderService->delete($this->physicalReplaceOrderDto);
        return ['????????????', 200, $num];
    }

    /**
     * ????????????????????????
     * @author weifeng
     */
    public function actionAudit(): ?array
    {
        try {
            $res = $this->physicalReplaceOrderService->audit($this->physicalReplaceOrderDto);
            return ['????????????', 200, $res];
        } catch (Exception $exception) {
            return ['????????????', 500, $exception->getMessage()];
        }
    }

    /**
     * ??????????????????????????????
     * @author weifeng
     */
    public function actionUpdateImport(): ?array
    {
        try {
            $data = $this->physicalReplaceOrderService->updateReplaceOrder($this->physicalReplaceOrderImport);
            if (!$data) {
                throw new Exception('????????????????????????????????????????????????');
            }
            return ['??????????????????', 200, $data];
        } catch (Exception $exception) {
            return ['??????????????????', 500, $exception->getMessage()];
        }
    }

    /**
     * ??????????????????
     * @author weifeng
     */

    public function actionUpdateStatus(): ?array
    {
        try {
            $data = $this->physicalReplaceOrderService->updatePrizeSendStatus($this->physicalReplaceOrderImport);
            return ['????????????????????????',200,$data];
        } catch (Exception $e) {
            return ['????????????????????????', 500, $e->getMessage()];
        }
    }


}