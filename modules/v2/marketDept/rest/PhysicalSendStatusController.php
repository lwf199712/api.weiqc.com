<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\rest;
use app\common\rest\AdminBaseController;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusForm;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusQuery;
use app\modules\v2\marketDept\service\PhysicalSendStatusService;
use Exception;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class PhysicalSendStatusController
 * @package app\modules\v2\marketDept\rest
 */
class PhysicalSendStatusController extends AdminBaseController
{
    /** @var PhysicalSendStatusService */
    public $physicalSendStatusService;
    /** @var PhysicalSendStatusForm */
    public $physicalSendStatusForm;
    /** @var PhysicalSendStatusQuery */
    public $physicalSendStatusQuery;

    public function __construct($id, $module,
                                PhysicalSendStatusService   $physicalSendStatusService,
                                PhysicalSendStatusQuery     $physicalSendStatusQuery,
                                PhysicalSendStatusForm      $physicalSendStatusForm,
                                $config = [])
    {
        $this->physicalSendStatusService    = $physicalSendStatusService;
        $this->physicalSendStatusQuery      = $physicalSendStatusQuery;
        $this->physicalSendStatusForm       = $physicalSendStatusForm;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'     => ['GET', 'HEAD', 'OPTIONS'],
            'export'    => ['GET', 'HEAD', 'OPTIONS'],
            'update'    => ['PUT', 'PATCH', 'OPTIONS'],
            'delete'    => ['DELETE', 'OPTIONS'],
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
                return $this->physicalSendStatusQuery;
                break;
            case 'actionUpdate':
            case 'actionDelete':
                return $this->physicalSendStatusForm;
                break;
            default:
                throw new HttpException('UnKnow ActionName ');
        }
    }

    /**
     * 发货信息首页
     * @return array
     * @author weifeng
     */
    public function actionIndex()
    {
        try {
            $data = $this->physicalSendStatusService->listData($this->physicalSendStatusQuery);
            return ['返回数据成功', 200, $data];
        } catch (Exception $exception) {
            return ['返回数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 发货信息导出
     * @return array
     * @author weifeng
     */

    public function actionExport()
    {
        try {
            $data = $this->physicalSendStatusService->exportReplaceOrder($this->physicalSendStatusQuery);
            return ['导出数据成功', 200, $data['exportName']];
        } catch (Exception $exception) {
            return ['导出数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 发货信息编辑
     * @author weifeng
     */

    public function actionUpdate()
    {
        try {
            $data = $this->physicalSendStatusService->update($this->physicalSendStatusForm);
            return ['编辑数据成功', 200, $data];
        } catch (Exception $exception) {
            return ['编辑数据失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 发货信息删除
     * @return array
     * @author weifeng
     */

    public function actionDelete()
    {
        $num = $this->physicalSendStatusService->delete($this->physicalSendStatusForm);
        return ['删除成功', 200, $num];
    }

}