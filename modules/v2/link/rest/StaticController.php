<?php
declare(strict_types=1);

namespace app\modules\v2\link\rest;

use app\common\facade\ExcelFacade;
use app\common\rest\AdminBaseController;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\domain\aggregate\StaticListAggregate;
use app\modules\v2\link\domain\dto\StaticUrlDeviceDto;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\dto\StaticUrlForm;
use app\modules\v2\link\domain\dto\StaticUrlIntervalAnalyzeDto;
use app\modules\v2\link\domain\dto\StaticUrlReportDto;
use app\modules\v2\link\domain\dto\StaticUrlVisitDetailDto;
use Exception;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class StaticController
 * @property StaticUrlDto                $staticUrlDto
 * @property staticUrlForm               $staticUrlForm
 * @property StaticUrlReportDto          $staticUrlReportDto
 * @property StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto
 * @property StaticUrlVisitDetailDto     $staticUrlVisitDetailDto
 * @property StaticUrlDeviceDto          $staticUrlDeviceDto
 * @property StaticListAggregate         $staticListAggregate
 * @property ActiveRecord                $dto
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
    /** @var StaticUrlReportDto */
    public $staticUrlReportDto;
    /** @var StaticUrlIntervalAnalyzeDto */
    public $staticUrlIntervalAnalyzeDto;
    /** @var StaticUrlVisitDetailDto */
    public $staticUrlVisitDetailDto;
    /** @var StaticUrlDeviceDto */
    public $staticUrlDeviceDto;
    /** @var ActiveRecord $dto */
    public $dto;

    public $modelClass = StaticUrlDo::class;

    public function __construct($id, $module,
                                StaticListAggregate $staticListAggregate,
                                StaticUrlDto $staticUrlDto,
                                StaticUrlForm $staticUrlForm,
                                StaticUrlReportDto $staticUrlReportDto,
                                StaticUrlIntervalAnalyzeDto $staticUrlIntervalAnalyzeDto,
                                StaticUrlVisitDetailDto $staticUrlVisitDetailDto,
                                StaticUrlDeviceDto $staticUrlDeviceDto,
                                $config = [])
    {
        $this->staticListAggregate         = $staticListAggregate;
        $this->staticUrlDto                = $staticUrlDto;
        $this->staticUrlForm               = $staticUrlForm;
        $this->staticUrlReportDto          = $staticUrlReportDto;
        $this->staticUrlIntervalAnalyzeDto = $staticUrlIntervalAnalyzeDto;
        $this->staticUrlVisitDetailDto     = $staticUrlVisitDetailDto;
        $this->staticUrlDeviceDto          = $staticUrlDeviceDto;
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
            'index'           => ['GET', 'HEAD'],
            'view'            => ['GET', 'HEAD'],
            'create'          => ['POST'],
            'update'          => ['PUT', 'PATCH'],
            'delete'          => ['DELETE'],
            'export'          => ['GET', 'HEAD'],
            'report'          => ['GET', 'HEAD'],
            'intervalAnalyze' => ['GET', 'HEAD'],
            'visitDetail'     => ['GET', 'HEAD'],
            'device'          => ['GET', 'HEAD'],
        ];
    }

    public function dtoMap(string $actionName): Model
    {
        return [
            'actionIndex'           => $this->staticUrlDto,
            'actionView'            => $this->staticUrlDto->setScenario(StaticUrlDto::ONE),
            'actionExport'          => $this->staticUrlDto,
            'actionCreate'          => $this->staticUrlForm,
            'actionUpdate'          => $this->staticUrlForm,
            'actionReport'          => $this->staticUrlReportDto,
            'actionIntervalAnalyze' => $this->staticUrlIntervalAnalyzeDto,
            'actionVisitDetail'     => $this->staticUrlVisitDetailDto,
            'actionDevice'          => $this->staticUrlDeviceDto,
        ][$actionName];
    }

    //-------------------------统计列表-----------------------------------//

    public function actionIndex(): array
    {
        $data = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        return ['成功返回数据', 200, $data];

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

    public function actionExport(): array
    {
        $data = $this->staticListAggregate->listStaticUrl($this->staticUrlDto);
        ExcelFacade::export($data);        //TODO export DATA
    }

    //-----------------------统计详情-----------------------//

    public function actionView(): array
    {
        $data = $this->staticListAggregate->viewStaticUrl((int)$this->staticUrlDto->id);
        return ['成功返回数据', 200, $data];
    }


    public function actionReport(): array
    {
        $data = $this->staticListAggregate->reportStaticUrl($this->staticUrlReportDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionIntervalAnalyze(): array
    {
        $data = $this->staticListAggregate->intervalAnalyzeStaticUrl($this->staticUrlIntervalAnalyzeDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionVisitDetail(): array
    {
        try {
            $data = $this->staticListAggregate->visitDetailStaticUrl($this->staticUrlVisitDetailDto);
            return ['成功返回数据', 200, $data];
        } catch (Exception $exception) {
            return ['数据查询失败', 500, $exception->getMessage()];
        }
    }

    //TODO 流量设备
    public function actionDevice(): array
    {
        $data = $this->staticListAggregate->DeviceStaticUrl($this->staticUrlVisitDetailDto);
        return ['成功返回数据', 200, $data];
    }

    //TODO 页面监控
    public function actionPageMonitor(): array
    {
        $data = $this->staticListAggregate->pageMonitorStaticUrl();
        return ['成功返回数据', 200, $data];
    }




}