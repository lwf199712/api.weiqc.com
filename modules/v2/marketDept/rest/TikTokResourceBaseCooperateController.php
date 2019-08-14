<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\marketDept\domain\aggregate\TikTokResourceBaseAggregate;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseCooperateDto;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseCooperateForm;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseImport;
use Exception;
use yii\base\Model;

/**
 * Class TikTokResourceBaseCooperateController
 * @property  TikTokResourceBaseCooperateDto  $tikTokResourceBaseCooperateDto
 * @property  tikTokResourceBaseAggregate     $tikTokResourceBaseAggregate
 * @property  TikTokResourceBaseCooperateForm $tikTokResourceBaseCooperateForm
 * @property  TikTokResourceBaseImport        $tikTokResourceBaseImport
 * @package app\modules\v2\marketDept\rest
 */
class TikTokResourceBaseCooperateController extends AdminBaseController
{
    /** @var TikTokResourceBaseCooperateDto */
    public $tikTokResourceBaseCooperateDto;
    /** @var TikTokResourceBaseAggregate */
    public $tikTokResourceBaseAggregate;
    /** @var TikTokResourceBaseCooperateForm */
    public $tikTokResourceBaseCooperateForm;
    /** @var TikTokResourceBaseImport */
    public $tikTokResourceBaseImport;

    public function __construct($id, $module,
                                TikTokResourceBaseCooperateDto $tikTokResourceBaseCooperateDto,
                                TikTokResourceBaseAggregate $tikTokResourceBaseAggregate,
                                TikTokResourceBaseCooperateForm $tikTokResourceBaseCooperateForm,
                                TikTokResourceBaseImport $tikTokResourceBaseImport,
                                $config = [])
    {
        $this->tikTokResourceBaseCooperateDto  = $tikTokResourceBaseCooperateDto;
        $this->tikTokResourceBaseAggregate     = $tikTokResourceBaseAggregate;
        $this->tikTokResourceBaseCooperateForm = $tikTokResourceBaseCooperateForm;
        $this->tikTokResourceBaseImport        = $tikTokResourceBaseImport;
        parent::__construct($id, $module, $config);
    }


    public function verbs()
    {
        return [
            'index'       => ['GET', 'HEAD'],
            'update'      => ['POST'],
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
            'actionIndex'       => $this->tikTokResourceBaseCooperateDto,
            'actionUpdate'      => $this->tikTokResourceBaseCooperateForm,
            'actionDelete'      => $this->tikTokResourceBaseCooperateDto,
            'actionExport'      => $this->tikTokResourceBaseCooperateDto,
            'actionDownload'    => $this->tikTokResourceBaseCooperateDto,
            'actionBatchUpdate' => $this->tikTokResourceBaseImport,
        ][$actionName];
    }


    public function actionIndex(): array
    {
        $data = $this->tikTokResourceBaseAggregate->listTikTokResourceBaseCooperate($this->tikTokResourceBaseCooperateDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionUpdate(): array
    {
        $nums = $this->tikTokResourceBaseAggregate->updateTikTokResourceBaseCooperate($this->tikTokResourceBaseCooperateForm);
        return ['更新成功', 200, $nums];
    }

    public function actionDelete(): array
    {
        $nums = $this->tikTokResourceBaseAggregate->deleteTikTokResourceBaseCooperate($this->tikTokResourceBaseCooperateDto->id);
        return ['更新成功', 200, $nums];
    }

    public function actionImport(): array
    {
        try {
            $nums = $this->tikTokResourceBaseAggregate->importTikTokResourceBaseCooperate($this->tikTokResourceBaseImport);
            return ['导入数据成功', 200, $nums];
        } catch (Exception $exception) {
            return ['导入数据失败', 500, $exception->getMessage()];
        }
    }

    public function actionExport(): void
    {
        $this->tikTokResourceBaseAggregate->exportTikTokResourceBaseCooperate($this->tikTokResourceBaseCooperateDto);

    }

    public function actionDownload(): void
    {
        $this->tikTokResourceBaseAggregate->exportTikTokResourceBaseCooperateExample();
    }


    public function actionBatchUpdate(): array
    {
        try {
            $nums = $this->tikTokResourceBaseAggregate->batchUpdateTikTokResourceBaseCooperate($this->tikTokResourceBaseImport);
            return ['导入数据成功', 200, $nums];
        } catch (Exception $exception) {
            return ['导入数据失败', 500, $exception->getMessage()];
        }
    }


}