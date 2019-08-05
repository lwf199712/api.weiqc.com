<?php
declare(strict_types=1);

namespace app\modules\v2\link\rest;


use app\common\rest\AdminBaseController;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\service\StaticListService;

/**
 * Class StaticController
 * @property StaticUrlDo $staticUrlDo
 * @property StaticListService $staticListService
 * @package app\modules\v2\link\rest
 */
class StaticController extends AdminBaseController
{
    /** @var StaticUrlDo  */
    public $staticUrlDo;
    /** @var StaticListService */
    public $staticListService;

    public $modelClass = StaticUrlDo::class;

    public function __construct($id, $module,
                                StaticUrlDo $staticUrlDo,
                                StaticListService $staticListService,
                                $config = [])
    {
        $this->staticListService = $staticListService;
        $this->staticUrlDo = $staticUrlDo;
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
        ];
    }


    public function index() : array
    {
        $this->staticUrlDo->load($this->request->get());
        if ($this->staticUrlDo->validate() === false){
            return ['输入参数有误',406,$this->staticUrlDo->getErrors()];
        }
        return $this->staticListService->listDataProvider($this->staticUrlDo);
    }
}