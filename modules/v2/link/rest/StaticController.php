<?php
declare(strict_types=1);

namespace app\modules\v2\link\rest;

use app\common\facade\ExcelFacade;
use app\common\rest\AdminBaseController;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\domain\aggregate\StaticListAggregate;
use app\modules\v2\link\domain\dto\SingleStaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class StaticController
 * @property StaticUrlDto        $staticUrlDto
 * @property SingleStaticUrlDto  $singleStaticUrlDto
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
    /** @var SingleStaticUrlDto */
    public $singleStaticUrlDto;
    /** @var StaticUrlForm */
    public $staticUrlForm;
    /** @var ActiveRecord $dto */
    public $dto;

    public $modelClass = StaticUrlDo::class;

    public function __construct($id, $module,
                                StaticListAggregate $staticListAggregate,
                                StaticUrlDto $staticUrlDto,
                                SingleStaticUrlDto $singleStaticUrlDto,
                                StaticUrlForm $staticUrlForm,
                                $config = [])
    {
        $this->staticListAggregate = $staticListAggregate;
        $this->singleStaticUrlDto  = $singleStaticUrlDto;
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

    public function dtoMap(string $actionName): Model
    {
        return [
            'actionIndex'  => $this->staticUrlDto,
            'actionView'   => $this->staticUrlDto,
            'actionExport' => $this->staticUrlDto,
            'actionCreate' => $this->staticUrlForm,
            'actionUpdate' => $this->staticUrlForm,
        ][$actionName];
    }


    public function actionIndex(): array
    {
        $data = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        return ['成功返回数据', 200, $data];

    }

    public function actionView(): array
    {
        $this->dto = $this->singleStaticUrlDto;
        $data      = $this->staticListAggregate->listStaticUrl();
    }


    public function actionExport(): array
    {
        $data      = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        ExcelFacade::export($data);        //TODO export DATA
    }


    public function actionCreate(): array
    {
        if ($this->staticListAggregate->addStaticUrl($this->staticUrlForm) === false) {
            return ['添加统计链接错误', 500];
        }
        return ['操作成功', 200];

    }

    public function actionUpdate(): array
    {
        if ($this->staticListAggregate->updateStaticUrl($this->staticUrlForm) === false) {
            return ['编辑统计链接错误', 500];
        }
        return ['操作成功', 200];
    }

    public function actionDelete(): array
    {

    }


}