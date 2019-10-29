<?php
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/15
 * Time: 14:15
 */

namespace app\modules\v2\advertDept\domain\factory;


use app\modules\v2\advertDept\domain\dto\WindowProjectForm;
use app\modules\v2\advertDept\domain\entity\WindowProjectEntiy;

interface IWindowProjectFactory
{
    public function creator(WindowProjectEntiy $windowProjectEntiy, array $data): bool ;
}