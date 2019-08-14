<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\marketDept\domain\aggregate\TikTokResourceBaseAggregate;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseDto;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseForm;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseImport;
use Exception;
use yii\base\Model;

/**
 * Class TikTokResourceBase
 * @property-read TikTokResourceBaseAggregate $tikTokResourceBaseAggregate
 * @property-read TikTokResourceBaseDto       $tikTokResourceBaseDto
 * @property-read TikTokResourceBaseForm      $tikTokResourceBaseForm
 * @property-read TikTokResourceBaseImport    $tikTokResourceBaseImport
 * @package app\modules\v2\marketDept\rest
 */
class TikTokResourceBaseController extends AdminBaseController
{
    /** @var TikTokResourceBaseAggregate */
    public $tikTokResourceBaseAggregate;
    /** @var TikTokResourceBaseDto */
    public $tikTokResourceBaseDto;
    /** @var TikTokResourceBaseForm */
    public $tikTokResourceBaseForm;
    /** @var TikTokResourceBaseImport */
    public $tikTokResourceBaseImport;

    public function __construct($id, $module,
                                TikTokResourceBaseDto $tikTokResourceBaseDto,
                                TikTokResourceBaseForm $tikTokResourceBaseForm,
                                TikTokResourceBaseAggregate $tikTokResourceBaseAggregate,
                                TikTokResourceBaseImport $tikTokResourceBaseImport,
                                $config = [])
    {
        $this->tikTokResourceBaseDto       = $tikTokResourceBaseDto;
        $this->tikTokResourceBaseForm      = $tikTokResourceBaseForm;
        $this->tikTokResourceBaseImport    = $tikTokResourceBaseImport;
        $this->tikTokResourceBaseAggregate = $tikTokResourceBaseAggregate;
        parent::__construct($id, $module, $config);
    }

    public function verbs()
    {
        return [
            'index'       => ['GET', 'HEAD'],
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
            'actionIndex'       => $this->tikTokResourceBaseDto,
            'actionUpdate'      => $this->tikTokResourceBaseForm,
            'actionDelete'      => $this->tikTokResourceBaseDto,
            'actionImport'      => $this->tikTokResourceBaseImport,
            'actionExport'      => $this->tikTokResourceBaseDto,
            'actionDownload'    => $this->tikTokResourceBaseDto,
            'actionBatchUpdate' => $this->tikTokResourceBaseImport,
        ][$actionName];
    }

    public function actionIndex(): array
    {
        $data = $this->tikTokResourceBaseAggregate->listTikTokResourceBase($this->tikTokResourceBaseDto);
        return ['成功返回数据', 200, $data];
    }

    public function actionUpdate(): array
    {
        $nums = $this->tikTokResourceBaseAggregate->updateTikTokResourceBase($this->tikTokResourceBaseForm);
        return ['更新成功', 200, $nums];
    }

    public function actionDelete(): array
    {
        try {
            $nums = $this->tikTokResourceBaseAggregate->deleteTikTokResourceBase((int)$this->tikTokResourceBaseDto->id);
            return ['删除成功', 200, $nums];
        } catch (Exception $exception) {
            return ['删除失败', 500, $exception->getMessage()];
        }
    }

    public function actionImport(): array
    {
        try {
            $nums = $this->tikTokResourceBaseAggregate->importTikTokResourceBase($this->tikTokResourceBaseImport);
            return ['导入数据成功', 200, $nums];
        } catch (Exception $exception) {
            return ['导入数据失败', 500, $exception->getMessage()];
        }
    }


    public function actionExport(): void
    {
        $this->tikTokResourceBaseAggregate->exportTikTokResourceBase($this->tikTokResourceBaseDto);
    }


    public function actionDownload(): void
    {
        $this->tikTokResourceBaseAggregate->exportTikTokResourceBaseExample();
    }

    public function actionBatchUpdate(): array
    {
        try {
            $nums = $this->tikTokResourceBaseAggregate->batchUpdateTikTokResourceBase($this->tikTokResourceBaseImport);
            return ['导入数据成功', 200, $nums];
        } catch (Exception $exception) {
            return ['导入数据失败', 500, $exception->getMessage()];
        }
    }


}