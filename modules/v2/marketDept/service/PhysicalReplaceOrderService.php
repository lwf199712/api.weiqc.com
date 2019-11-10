<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\service;

use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderDto;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderForm;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderImport;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderQuery;

interface PhysicalReplaceOrderService
{
    /**
     * 实物置换订单首页
     * @param PhysicalReplaceOrderQuery $physicalReplaceOrderQuery
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function listData(PhysicalReplaceOrderQuery $physicalReplaceOrderQuery);

    /**
     * 实物置换订单导入
     * @param PhysicalReplaceOrderImport $physicalReplaceOrderImport
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */

    public function importReplaceOrder(PhysicalReplaceOrderImport $physicalReplaceOrderImport);

    /**
     * 实物置换订单更新导入
     * @param PhysicalReplaceOrderImport $physicalReplaceOrderImport
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function updateReplaceOrder(PhysicalReplaceOrderImport $physicalReplaceOrderImport);

    /**
     * 实物置换订单导出
     * @param PhysicalReplaceOrderQuery $physicalReplaceOrderQuery
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */

    public function exportReplaceOrder(PhysicalReplaceOrderQuery $physicalReplaceOrderQuery);

    /**
     * 实物置换订单编辑
     * @param PhysicalReplaceOrderForm $physicalReplaceOrderForm
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function update(PhysicalReplaceOrderForm $physicalReplaceOrderForm): bool;

    /**
     * 实物置换订单删除
     * @param PhysicalReplaceOrderDto $physicalReplaceOrderDto
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */

    public function delete(PhysicalReplaceOrderDto $physicalReplaceOrderDto);

    /**
     * 实物置换订单审核
     * @param PhysicalReplaceOrderDto $physicalReplaceOrderDto
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function audit(PhysicalReplaceOrderDto $physicalReplaceOrderDto);


    /**
     * 实物置换订单更新寄出状态
     * @param PhysicalReplaceOrderImport $physicalReplaceOrderImport
     * @return mixed
     * @author weifeng
     * @date 2019/10/31
     */
    public function updatePrizeSendStatus(PhysicalReplaceOrderImport $physicalReplaceOrderImport);




}