<?php
declare(strict_types=1);

namespace app\modules\v2\link\rest;

use app\common\facade\ExcelFacade;
use app\common\rest\AdminBaseController;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\domain\aggregate\StaticListAggregate;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use yii\db\ActiveRecord;

/**
 * Class StaticController
 * @property StaticUrlDto        $staticUrlDto
 * @property staticUrlForm       $staticUrlForm
 * @property StaticListAggregate $staticListAggregate
 * @property ActiveRecord        $dto
 * @package app\modules\v2\link\rest
 */
class StaticController extends AdminBaseController
{

    /** @var StaticListAggregate */
    public $staticListAggregate;
    /** @var StaticUrlDto */
    public $staticUrlDto;
    /** @var StaticUrlForm */
    public $staticUrlForm;
    /** @var ActiveRecord $dto */
    public $dto;

    public $modelClass = StaticUrlDo::class;

    public function __construct($id, $module,
                                StaticListAggregate $staticListAggregate,
                                StaticUrlDto $staticUrlDto,
                                StaticUrlForm $staticUrlForm,
                                $config = [])
    {
        $this->staticListAggregate = $staticListAggregate;
        $this->staticUrlDto        = $staticUrlDto;
        $this->staticUrlForm       = $staticUrlForm;
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
            'index'  => ['GET', 'HEAD'],
            'view'   => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
            'export' => ['GET', 'HEAD'],
        ];
    }


    public function actionIndex(): array
    {
        $this->dto = $this->staticUrlDto;
        $data      = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        return ['成功返回数据', 200, $data];
    }


    public function actionExport(): array
    {
        $this->dto = $this->staticUrlDto;
        $data      = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        ExcelFacade::export($data);        //TODO export DATA
    }


    public function actionCreate(): array
    {
        $this->dto = $this->staticUrlForm;
        if ($this->staticListAggregate->addStaticUrl($this->staticUrlForm) === false) {
            return ['添加统计链接错误', 500];
        }
        return ['操作成功', 200];

    }

    public function actionUpdate(): array
    {
        $this->dto = $this->staticUrlForm;
    }

    public function actionDelete(): array
    {

    }


}