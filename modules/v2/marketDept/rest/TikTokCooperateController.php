<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\marketDept\domain\aggregate\TikTokCooperateAggregate;
use app\modules\v2\marketDept\domain\dto\TikTokCooperateDto;
use app\modules\v2\marketDept\domain\dto\TikTokCooperatePersonalInfoForm;
use Exception;
use yii\base\Model;

/**
 * Class TikTokCooperateController
 * @property-read TikTokCooperateAggregate   $tikTokCooperateAggregate
 * @property TikTokCooperateDto              $tikTokCooperateDto
 * @property TikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm
 * @package app\modules\v2\marketDept\rest
 */
class TikTokCooperateController extends AdminBaseController
{
    /** @var TikTokCooperateAggregate */
    public $tikTokCooperateAggregate;
    /** @var TikTokCooperateDto */
    public $tikTokCooperateDto;
    /** @var TikTokCooperatePersonalInfoForm */
    public $tikTokCooperatePersonalInfoForm;

    public function __construct($id, $module,
                                TikTokCooperateAggregate $tikTokCooperateAggregate,
                                TikTokCooperateDto $tikTokCooperateDto,
                                TikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm,
                                $config = [])
    {
        $this->tikTokCooperateAggregate        = $tikTokCooperateAggregate;
        $this->tikTokCooperateDto              = $tikTokCooperateDto;
        $this->tikTokCooperatePersonalInfoForm = $tikTokCooperatePersonalInfoForm;
        parent::__construct($id, $module, $config);
    }

    public function verbs()
    {
        return [
            'index'       => ['GET', 'HEAD'],
            'create'      => ['POST'],
            'update'      => ['PUT', 'PATCH'],
            'delete'      => ['DELETE'],
            'import'      => ['POST'],
            'export'      => ['GET', 'HEAD'],
            'download'    => ['GET', 'HEAD'],
            'batchUpdate' => ['POST'],
        ];
    }


    public function dtoMap(string $actionName): Model
    {
        return [
            'actionIndex'  => $this->tikTokCooperateDto,
            'actionCreate' => $this->tikTokCooperatePersonalInfoForm,
            'actionUpdate' => $this->tikTokCooperateDto,

        ][$actionName];
    }

    public function actionIndex(): array
    {
        $data = $this->tikTokCooperateAggregate->listTikTokCooperate($this->tikTokCooperateDto);
        return ['成功返回数据', 200, $data];
    }


    public function actionCreate(): array
    {
        try {
            $this->tikTokCooperateAggregate->createTikTokCooperate($this->tikTokCooperatePersonalInfoForm);
            return ['新增成功', 200];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    public function actionUpdate(): array
    {
        try {
            $this->tikTokCooperateAggregate->updateTikTokCooperate($this->tikTokCooperateDto);
            return ['修改成功', 200];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }


    public function actionDelete(): array
    {
        $num = $this->tikTokCooperateAggregate->deleteTikTikCooperate((int)$this->tikTokCooperateDto->id);
        return ['删除成功', 200 ,$num];
    }

    public function actionImport(): array
    {

    }


    public function actionExport(): void
    {


    }


    public function actionDownload(): void
    {

    }

    public function actionBatchUpdate(): array
    {

    }


}