<?php
declare(strict_types=1);

namespace app\modules\v2\link\rest;


use app\common\rest\AdminBaseController;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\domain\aggregate\StaticListAggregate;
use app\modules\v2\link\domain\dto\StaticUrlDto;

/**
 * Class StaticController
 * @property StaticUrlDto $staticUrlDto
 * @property StaticListAggregate $staticListAggregate
 * @package app\modules\v2\link\rest
 */
class StaticController extends AdminBaseController
{
    /** @var StaticUrlDto  */
    public $staticUrlDto;
    /** @var StaticListAggregate */
    public $staticListAggregate;

    public $modelClass = StaticUrlDo::class;

    public function __construct($id, $module,
                                StaticUrlDto $staticUrlDto,
                                StaticListAggregate $staticListAggregate,
                                $config = [])
    {
        $this->staticListAggregate = $staticListAggregate;
        $this->staticUrlDto = $staticUrlDto;
        parent::__construct($id, $module, $config);
    }

    /**
     * Declares the allowed HTTP verbs.
     *
     * @return array
     * @author: zhuozhen
     */
    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD'],
            'export' => ['GET','HEAD'],
        ];
    }


    public function index() : array
    {
        $this->staticUrlDto->load($this->request->get());
        if ($this->staticUrlDto->validate() === false){
            return ['输入参数有误',406,$this->staticUrlDto->getErrors()];
        }
        $data = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        return ['成功返回数据',200,$data];
    }


    public function export() : array
    {
        $this->staticUrlDto->load($this->request->get());
        if ($this->staticUrlDto->validate() === false){
            return ['输入参数有误',406,$this->staticUrlDto->getErrors()];
        }
        $data = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        //TODO export DATA
    }
}