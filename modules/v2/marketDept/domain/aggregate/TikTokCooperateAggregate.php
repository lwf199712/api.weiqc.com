<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\aggregate;


use app\common\facade\ExcelFacade;
use app\common\utils\DbOperation;
use app\modules\v2\marketDept\domain\dto\TikTokCooperateDto;
use app\modules\v2\marketDept\domain\dto\TikTokCooperateImport;
use app\modules\v2\marketDept\domain\dto\TikTokCooperatePersonalInfoForm;
use app\modules\v2\marketDept\domain\repository\TikTokCooperateDoManager;
use app\modules\v2\marketDept\domain\entity\TikTokCooperateEntity as TikTokCooperateAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class TikTokCooperateAggregate
 * @property TikTokCooperateAggregateRoot $tikTokCooperateAggregateRoot
 * @property TikTokCooperateDoManager     $tikTokCooperateDoManager
 * @property TikTokCooperateDto           $tikTokCooperateDto
 * @package app\modules\v2\marketDept\domain\aggregate
 */
class TikTokCooperateAggregate extends BaseObject
{

    public $tikTokCooperateAggregateRoot;
    /** @var TikTokCooperateDto */
    private $tikTokCooperateDto;
    /** @var TikTokCooperateDoManager */
    private $tikTokCooperateDoManager;

    public function __construct(
        TikTokCooperateAggregateRoot $tikTokCooperateAggregateRoot,
        TikTokCooperateDoManager $tikTokCooperateDoManager,
        TikTokCooperateDto $tikTokCooperateDto,
        $config = [])
    {
        $this->tikTokCooperateAggregateRoot = $tikTokCooperateAggregateRoot;
        $this->tikTokCooperateDto           = $tikTokCooperateDto;
        $this->tikTokCooperateDoManager     = $tikTokCooperateDoManager;
        parent::__construct($config);
    }

    /**
     * @param TikTokCooperateDto $TikTokCooperateDto
     * @return array
     * @author zhuozhen
     */
    public function listTikTokCooperate(TikTokCooperateDto $TikTokCooperateDto): array
    {
        return $this->tikTokCooperateDoManager->listDataProvider($TikTokCooperateDto)->getModels();

    }

    /**
     * @param TikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function createTikTokCooperate(tikTokCooperatePersonalInfoForm $tikTokCooperatePersonalInfoForm): bool
    {
        $result =  $this->tikTokCooperateAggregateRoot->createEntity($tikTokCooperatePersonalInfoForm);
        if ($result === false){
            throw new Exception('新增抖音合作核实失败');
        }
        return $result;
    }

    /**
     * @param TikTokCooperateDto $tikTokCooperateDto
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateTikTokCooperate(TikTokCooperateDto $tikTokCooperateDto): bool
    {
        $result =  $this->tikTokCooperateAggregateRoot->updateEntity($tikTokCooperateDto);
        if ($result === false){
            throw new Exception('更新抖音合作核实失败');
        }
        return $result;
    }

    /**
     * @param int $tikTokCooperateId
     * @return int
     * @author zhuozhen
     */
    public function deleteTikTikCooperate(int $tikTokCooperateId): int
    {
        return $this->tikTokCooperateAggregateRoot->deleteEntity($tikTokCooperateId);
    }

    /**
     * @param TikTokCooperateImport $tikTokCooperateImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function importTikTokCooperate(TikTokCooperateImport $tikTokCooperateImport): int
    {
        $tikTokCooperateImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                             = ExcelFacade::import($tikTokCooperateImport->excelFile->tempName);
        return Yii::$app->db->createCommand()->batchInsert($this->tikTokCooperateAggregateRoot::tableName(), array_diff($this->tikTokCooperateAggregateRoot->attributes(), ['id']), $data)->execute();
    }

    /**
     * @param TikTokCooperateDto $tikTokCooperateDto
     * @author zhuozhen
     */
    public function exportTikTokCooperate(TikTokCooperateDto $tikTokCooperateDto): void
    {
        $data = $this->tikTokCooperateDoManager->listDataProvider($tikTokCooperateDto)->getModels();
        array_walk($data, static function (&$value) {
            unset($value['id']);
        });
        ExcelFacade::export(array_merge([array_diff(array_values($this->tikTokCooperateDto->attributeLabels()), ['ID'])], $data), '抖音合作审核');
    }

    /**
     * @author zhuozhen
     */
    public function exportTikTokCooperateExample(): void
    {
        $data = [array_diff(array_values($this->tikTokCooperateDto->attributeLabels()), ['ID'])];
        ExcelFacade::export($data, '抖音合作审核模板');
    }


    /**
     * @param TikTokCooperateImport $tikTokCooperateImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function batchUpdateTikTokCooperate(TikTokCooperateImport $tikTokCooperateImport) :int
    {
        $tikTokCooperateImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                             = ExcelFacade::import($tikTokCooperateImport->excelFile->tempName);
        return DbOperation::batchInsertUpdate($this->tikTokCooperateAggregateRoot::tableName(), array_diff($this->tikTokCooperateAggregateRoot->attributes(), ['id']), $data);
    }


}