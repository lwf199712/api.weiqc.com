<?php declare(strict_types=1);

namespace app\modules\v2\marketDept\service\impl;

use app\common\facade\ExcelFacade;
use app\models\dataObject\PhysicalSendStatusDo;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusForm;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusQuery;
use app\modules\v2\marketDept\domain\entity\PhysicalSendStatusEntity;
use app\modules\v2\marketDept\domain\repository\PhysicalSendStatusDoManager;
use app\modules\v2\marketDept\service\PhysicalSendStatusService;
use Exception;
use yii\base\BaseObject;

class PhysicalSendStatusImpl extends BaseObject implements PhysicalSendStatusService
{
    /** @var PhysicalSendStatusQuery */
    public $physicalSendStatusQuery;
    /** @var PhysicalSendStatusDoManager */
    public $physicalSendStatusDoManager;
    /** @var PhysicalSendStatusDo */
    public $physicalSendStatusDo;
    /** @var PhysicalSendStatusEntity */
    public $physicalSendStatusEntity;

    public function __construct(
        PhysicalSendStatusDo        $physicalSendStatusDo,
        PhysicalSendStatusDoManager $physicalSendStatusDoManager,
        PhysicalSendStatusQuery     $physicalSendStatusQuery,
        PhysicalSendStatusEntity    $physicalSendStatusEntity,
        $config = [])
    {
        $this->physicalSendStatusDo         = $physicalSendStatusDo;
        $this->physicalSendStatusDoManager  = $physicalSendStatusDoManager;
        $this->physicalSendStatusQuery      = $physicalSendStatusQuery;
        $this->physicalSendStatusEntity     = $physicalSendStatusEntity;

        parent::__construct($config);
    }

    /**
     * 发货信息首页
     * @param PhysicalSendStatusQuery $physicalSendStatusQuery
     * @return array
     * @author weifeng
     */
    public function listData(PhysicalSendStatusQuery $physicalSendStatusQuery): array
    {
        $list['lists']      = $this->physicalSendStatusDoManager->listDataProvider($physicalSendStatusQuery)->getModels();
        $list['totalCount'] = $this->physicalSendStatusDoManager->listDataProvider($physicalSendStatusQuery)->getTotalCount();
        return $list;
    }


    /**
     * 导出订单
     * @param PhysicalSendStatusQuery $physicalSendStatusQuery
     * @return mixed|void
     * @author weifeng
     */
    public function exportReplaceOrder(PhysicalSendStatusQuery $physicalSendStatusQuery)
    {
        $tableHeader = ['收件人', '联系电话', '收货地址', '快递单号'];
        $data = $this->listData($physicalSendStatusQuery)['lists'];
        foreach ($data as &$value) {
            unset($value['id'], $value['rp_id']);
        }
        ExcelFacade::export(array_merge([$tableHeader], $data), '实物置换发货信息数据', ['C']);
        //如果导出成功，上面一步不会报错，直接return
        return ['status' => 1];
    }

    /**
     * 编辑订单
     * @param PhysicalSendStatusForm $physicalSendStatusForm
     * @return bool
     * @throws \yii\db\Exception
     * @throws Exception
     * @author weifeng
     */
    public function update(PhysicalSendStatusForm $physicalSendStatusForm): bool
    {
        //更新实体
        $res = $this->physicalSendStatusEntity->updateEntity($physicalSendStatusForm);
        if (!$res) {
            throw new Exception('编辑失败！请重试！！！');
        }
        return $res;
    }

    /**
     * 删除订单
     * @param PhysicalSendStatusForm $physicalSendStatusForm
     * @return int|mixed
     * @throws Exception
     * @author weifeng
     */
    public function delete(PhysicalSendStatusForm $physicalSendStatusForm)
    {
        $res = $this->physicalSendStatusEntity->deleteEntity($physicalSendStatusForm);
        if (!$res) {
            throw new Exception('删除失败！请重试！！！');
        }
        return $res;
    }
}