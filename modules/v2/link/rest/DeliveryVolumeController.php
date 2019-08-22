<?php declare(strict_types=1);


namespace app\modules\v2\link\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\aggregate\DeliveryVolumeAggregate;
use app\modules\v2\link\domain\dto\DeliveryVolumeDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeForm;
use Exception;
use yii\base\Model;

/**
 * Class DeliveryVolumeController
 * @property-read    DeliveryVolumeAggregate $deliveryVolumeAggregate
 * @property-read    deliveryVolumeDto       $deliveryVolumeDto
 * @property-read    DeliveryVolumeForm      $deliveryVolumeForm
 * @package app\modules\v2\link\rest
 */
class DeliveryVolumeController extends AdminBaseController
{
    /** @var DeliveryVolumeAggregate */
    private $deliveryVolumeAggregate;
    /** @var DeliveryVolumeDto */
    public $deliveryVolumeDto;
    /** @var DeliveryVolumeForm */
    public $deliveryVolumeForm;

    public function __construct($id, $module,
                                DeliveryVolumeAggregate $deliveryVolumeAggregate,
                                DeliveryVolumeDto $deliveryVolumeDto,
                                DeliveryVolumeForm $deliveryVolumeForm,
                                $config = [])
    {
        $this->deliveryVolumeAggregate = $deliveryVolumeAggregate;
        $this->deliveryVolumeDto       = $deliveryVolumeDto;
        $this->deliveryVolumeForm      = $deliveryVolumeForm;
        parent::__construct($id, $module, $config);
    }


    protected function verbs() : array
    {
        return [
            'index'        => ['GET', 'HEAD'],
            'view'         => ['GET', 'HEAD'],
            'create'       => ['POST'],
            'update'       => ['POST', 'PUT'],
            'delete'       => ['DELETE'],
            'generateData' => ['GET'],
        ];
    }


    public function dtoMap(string $actionName): Model
    {
        return [
            'actionIndex'        => $this->deliveryVolumeDto,
            'actionView'         => $this->deliveryVolumeDto->setScenario(DeliveryVolumeDto::VIEW),
            'actionCreate'       => $this->deliveryVolumeForm,
            'actionUpdate'       => $this->deliveryVolumeForm->setScenario(DeliveryVolumeForm::UPDATE),
            'actionDelete'       => $this->deliveryVolumeDto->setScenario(DeliveryVolumeDto::DELETE),
            'actionGenerateData' => $this->deliveryVolumeDto->setScenario(DeliveryVolumeDto::GENERATE_DATA),
        ][$actionName];
    }


    public function actionIndex() : array
    {
        $data = $this->deliveryVolumeAggregate->listDeliveryVolume($this->deliveryVolumeDto);
        return ['成功返回数据',200,$data];
    }


    public function actionView() : array
    {
        $data = $this->deliveryVolumeAggregate->viewDeliveryVolume((int)$this->deliveryVolumeDto->id);
        return ['成功返回数据',200,$data];
    }


    public function actionCreate() : array
    {
        try {
            $this->deliveryVolumeAggregate->createDeliveryVolume($this->deliveryVolumeForm);
            return ['创建成功', 200];
        }catch (Exception $exception){
            return ['创建失败', 500 ,$exception->getMessage()];
        }
    }

    public function actionUpdate() : array
    {
        try {
            $this->deliveryVolumeAggregate->updateDeliveryVolume($this->deliveryVolumeForm);
            return ['更新成功', 200];
        }catch (Exception $exception){
            return ['创建失败', 500 ,$exception->getMessage()];
        }
    }


    public function actionDelete() : array
    {
        try {
            $this->deliveryVolumeAggregate->deleteDeliveryVolume((int)$this->deliveryVolumeDto->id);
            return ['删除成功', 200];
        }catch (Exception $exception){
            return ['删除失败', 500 ,$exception->getMessage()];
        }
    }


    public function actionGenerateData() : array
    {
        try {
            $this->deliveryVolumeAggregate->generateDeliveryData($this->deliveryVolumeDto);
            return ['生成数据成功', 200];
        }catch (Exception $exception){
            return ['生成数据失败', 500 ,$exception->getMessage()];
        }
    }


}