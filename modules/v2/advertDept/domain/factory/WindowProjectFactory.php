<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/15
 * Time: 14:27
 */

namespace app\modules\v2\advertDept\domain\factory;


use app\modules\v2\advertDept\domain\dto\WindowProjectForm;
use app\modules\v2\advertDept\domain\entity\WindowProjectEntiy;

class WindowProjectFactory implements IWindowProjectFactory
{
    /**
     * @param WindowProjectEntiy $windowProjectEntiy
     * @param array $data
     * @return bool
     * author: pengguochao
     * Date Time 2019/10/16 16:12
     */
    public function creator(WindowProjectEntiy $windowProjectEntiy, array $data): bool
    {
        $windowProjectEntiy->consume = $data['consume'];
        $windowProjectEntiy->total_turnover = $data['total_turnover'];
        $windowProjectEntiy->real_turnover = $data['real_turnover'];
        $windowProjectEntiy->transaction_data = $data['transaction_data'];
        $windowProjectEntiy->period = $data['period'];
        $windowProjectEntiy->create_at = time();
        return $windowProjectEntiy->save();
    }
}