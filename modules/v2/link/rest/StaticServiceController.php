<?php


namespace app\modules\v2\link\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\aggregate\StaticServiceAggregate;
use app\modules\v2\link\domain\dto\StaticServiceDto;
use RuntimeException;
use yii\base\Model;

class StaticServiceController extends AdminBaseController
{
    private $staticServiceAggregate;
    private $staticServiceDto;

    public function __construct($id, $module,
                                StaticServiceAggregate $staticServiceAggregate,
                                StaticServiceDto $staticServiceDto,
                                $config = [])
    {
        $this->staticServiceAggregate = $staticServiceAggregate;
        $this->staticServiceDto       = $staticServiceDto;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD'],
        ];
    }

    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->staticServiceDto;
                break;
            default:
                throw new RuntimeException('unKnow actionName', 500);
        }
    }

    public function actionIndex(): array
    {
       // $data = $this->staticServiceAggregate()
        var_dump('sds');die();
        return ['返回数据成功', '200', 'dsf'];
    }

}
