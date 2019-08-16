<?php declare(strict_types=1);

namespace app\modules\v2\advertDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\advertDept\domain\aggregate\TmallOrderAggregate;
use app\modules\v2\advertDept\domain\dto\TmallOrderDto;
use app\modules\v2\advertDept\domain\dto\TmallOrderImport;
use yii\base\Model;

/**
 * Class TmallOrderController
 * @property-read TmallOrderAggregate $tmallOrderAggregate
 * @property-read TmallOrderImport $tmallOrderImport
 * @property-read TmallOrderDto    $tmallOrderDto
 * @package app\modules\v2\advertDept\rest
 */
class TmallOrderController extends AdminBaseController
{
    /** @var TmallOrderAggregate  */
    public $tmallOrderAggregate;
    /** @var TmallOrderDto */
    public $tmallOrderDto;
    /** @var TmallOrderImport */
    public $tmallOrderImport;

    public function __construct($id, $module,
                                TmallOrderAggregate $tmallOrderAggregate,
                                TmallOrderDto $tmallOrderDto,
                                TmallOrderImport $tmallOrderImport,
                                $config = [])
    {
        $this->tmallOrderAggregate = $tmallOrderAggregate;
        $this->tmallOrderDto    = $tmallOrderDto;
        $this->tmallOrderImport = $tmallOrderImport;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'  => ['GET', 'HEAD'],
            'import' => ['POST'],
        ];
    }


    public function dtoMap(string $actionName): Model
    {
        return [
            'actionIndex'  => $this->tmallOrderDto,
            'actionImport' => $this->tmallOrderImport,
        ][$actionName];
    }

    public function actionIndex()
    {
        $data = $this->tmallOrderAggregate->listTmallOrder($this->tmallOrderDto);
        return ['成功返回数据',200,$data];
    }

    public function actionImport()
    {
        try {
            $nums = $this->tmallOrderAggregate->ImportTmallOrder($this->tmallOrderImport);
            return ['导入成功', 200 , $nums];
        }catch (\Exception $exception){
            return ['导入失败', 500 ,$exception];
        }
    }


}