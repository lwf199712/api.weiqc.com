<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;

use yii\base\Model;

class StaticServiceDto extends Model
{
    public $serviceAccount;
    public $serviceName;

//    public function rules(): array
//    {
//        return [''];
//    }

    public function attributes(): array
    {
        return [
            'serviceAccount' => '公众号账号',
            'serviceName'    => '公众号名称',
        ];
    }
}
