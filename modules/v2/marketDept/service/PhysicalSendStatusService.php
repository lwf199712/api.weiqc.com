<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\service;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusForm;
use app\modules\v2\marketDept\domain\dto\PhysicalSendStatusQuery;

interface PhysicalSendStatusService
{
    /**
     * 发货信息首页
     * @param PhysicalSendStatusQuery $physicalSendStatusQuery
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function listData(PhysicalSendStatusQuery $physicalSendStatusQuery);

    /**
     * 发货信息导出
     * @param PhysicalSendStatusQuery $physicalSendStatusQuery
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */

    public function exportReplaceOrder(PhysicalSendStatusQuery $physicalSendStatusQuery);

    /**
     * 发货信息编辑
     * @param PhysicalSendStatusForm $physicalSendStatusForm
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function update(PhysicalSendStatusForm $physicalSendStatusForm): bool;

    /**
     * 发货信息删除
     * @param PhysicalSendStatusForm $physicalSendStatusForm
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function delete(PhysicalSendStatusForm $physicalSendStatusForm);
}