<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\aggregate;

use app\common\facade\ExcelFacade;
use app\common\utils\DbOperation;
use app\models\dataObject\TikTokResourceBaseCooperateDo;
use app\models\dataObject\TikTokResourceBaseDo;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseCooperateDto;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseCooperateForm;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseDto;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseForm;
use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseImport;
use app\modules\v2\marketDept\domain\repository\TikTokResourceBaseCooperateDoManager;
use app\modules\v2\marketDept\domain\repository\TikTokResourceBaseDoManager;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class TikTokResourceBaseAggregate
 * @property TikTokResourceBaseDo                      $tikTokResourceBaseDo
 * @property TikTokResourceBaseCooperateDo             $tikTokResourceBaseCooperateDo
 * @property-read TikTokResourceBaseDoManager          $tikTokResourceBaseDoManager
 * @property-read TikTokResourceBaseCooperateDoManager $tikTokResourceBaseCooperateDoManager
 * @package app\modules\v2\marketDept\domain\aggregate
 */
class TikTokResourceBaseAggregate extends BaseObject
{

    /** @var TikTokResourceBaseDoManager */
    public $tikTokResourceBaseDoManager;
    /** @var TikTokResourceBaseCooperateDoManager  */
    public $tikTokResourceBaseCooperateDoManager;
    /** @var TikTokResourceBaseDo */
    public $tikTokResourceBaseDo;
    /** @var TikTokResourceBaseCooperateDo */
    public $tikTokResourceBaseCooperateDo;

    public function __construct(
        TikTokResourceBaseDoManager $tikTokResourceBaseDoManager,
        TikTokResourceBaseCooperateDoManager $tikTokResourceBaseCooperateDoManager,
        TikTokResourceBaseDo $tikTokResourceBaseDo,
        TikTokResourceBaseCooperateDo $tikTokResourceBaseCooperateDo,
        $config = [])
    {
        $this->tikTokResourceBaseDoManager          = $tikTokResourceBaseDoManager;
        $this->tikTokResourceBaseCooperateDoManager = $tikTokResourceBaseCooperateDoManager;
        $this->tikTokResourceBaseDo                 = $tikTokResourceBaseDo;
        $this->tikTokResourceBaseCooperateDo        = $tikTokResourceBaseCooperateDo;
        parent::__construct($config);
    }


    //--------------------------------------抖音资源库-----------------------------------//

    /**
     * @param TikTokResourceBaseDto $tikTokResourceBaseDto
     * @return array
     * @author zhuozhen
     */
    public function listTikTokResourceBase(TikTokResourceBaseDto $tikTokResourceBaseDto): array
    {
        return $this->tikTokResourceBaseDoManager->listDataProvider($tikTokResourceBaseDto)->getModels();
    }

    /**
     * @param TikTokResourceBaseForm $tikTokResourceBaseForm
     * @return int
     * @author zhuozhen
     */
    public function updateTikTokResourceBase(TikTokResourceBaseForm $tikTokResourceBaseForm): int
    {
        return $this->tikTokResourceBaseDo::updateAll($tikTokResourceBaseForm, 'id = :id', [':id' => $tikTokResourceBaseForm->id]);
    }

    /**
     * @param int $tikTokResourceBaseId
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function deleteTikTokResourceBase(int $tikTokResourceBaseId): int
    {
        $tikTokResourceBase = $this->tikTokResourceBaseDo::findOne($tikTokResourceBaseId);
        if ($tikTokResourceBase === null) {
            throw new Exception('找不到该资源');
        }

        if ($tikTokResourceBase->tikTokResourceBaseCooperateDo->all() !== null) {
            throw new Exception('该资源下有合作资源，无法删除');
        }
        return $this->tikTokResourceBaseDo::deleteAll(['id' => $tikTokResourceBaseId]);
    }

    /**
     * @param TikTokResourceBaseImport $tikTokResourceBaseImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function importTikTokResourceBase(TikTokResourceBaseImport $tikTokResourceBaseImport): int
    {
        $tikTokResourceBaseImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                                = ExcelFacade::import($tikTokResourceBaseImport->excelFile->tempName);
        return Yii::$app->db->createCommand()->batchInsert($this->tikTokResourceBaseDo::tableName(), array_diff($this->tikTokResourceBaseDo->attributes(), ['id']), $data)->execute();
    }

    /**
     * @param TikTokResourceBaseDto $tikTokResourceBaseDto
     * @author zhuozhen
     */
    public function exportTikTokResourceBase(TikTokResourceBaseDto $tikTokResourceBaseDto): void
    {
        $data = $this->tikTokResourceBaseDoManager->listDataProvider($tikTokResourceBaseDto)->getModels();
        array_walk($data, static function (&$value){
            unset($value['id']);
        });
        ExcelFacade::export(array_merge([array_diff(array_values($this->tikTokResourceBaseDo->attributeLabels()),['ID'])],$data),'抖音资源库');
    }

    /**
     * @author zhuozhen
     */
    public function exportTikTokResourceBaseExample() : void
    {
        $data = [array_diff(array_values($this->tikTokResourceBaseDo->attributeLabels()),['ID'])];
        ExcelFacade::export($data,'抖音资源库模板');
    }

    /**
     * @param TikTokResourceBaseImport $tikTokResourceBaseImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function batchUpdateTikTokResourceBase(TikTokResourceBaseImport $tikTokResourceBaseImport) : int
    {
        $tikTokResourceBaseImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                                = ExcelFacade::import($tikTokResourceBaseImport->excelFile->tempName);
        return DbOperation::batchInsertUpdate($this->tikTokResourceBaseDo::tableName(),array_diff($this->tikTokResourceBaseDo->attributes(),['id']),$data);
    }


    //----------------------------------抖音资源库-合作审核 -----------------------------------//

    /**
     * @param TikTokResourceBaseCooperateDto $tikTokResourceBaseCooperateDto
     * @return array
     * @author zhuozhen
     */
    public function listTikTokResourceBaseCooperate(TikTokResourceBaseCooperateDto $tikTokResourceBaseCooperateDto): array
    {
        return $this->tikTokResourceBaseCooperateDoManager->listDataProvider($tikTokResourceBaseCooperateDto)->getModels();
    }

    /**
     * @param TikTokResourceBaseCooperateForm $tikTokResourceBaseCooperateForm
     * @return int
     * @author zhuozhen
     */
    public function updateTikTokResourceBaseCooperate(TikTokResourceBaseCooperateForm $tikTokResourceBaseCooperateForm) : int
    {
        return $this->tikTokResourceBaseCooperateDo::updateAll($tikTokResourceBaseCooperateForm, 'id = :id', [':id' => $tikTokResourceBaseCooperateForm->id]);
    }

    /**
     * @param int $tikTokResourceBaseCooperateId
     * @return int
     * @author zhuozhen
     */
    public function deleteTikTokResourceBaseCooperate(int $tikTokResourceBaseCooperateId) : int
    {
        return $this->tikTokResourceBaseDo::deleteAll(['id' => $tikTokResourceBaseCooperateId]);
    }

    /**
     * @param TikTokResourceBaseImport $tikTokResourceBaseImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function importTikTokResourceBaseCooperate(TikTokResourceBaseImport $tikTokResourceBaseImport) : int
    {
        $tikTokResourceBaseImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                                = ExcelFacade::import($tikTokResourceBaseImport->excelFile->tempName);
        return Yii::$app->db->createCommand()->batchInsert($this->tikTokResourceBaseCooperateDo::tableName(), array_diff($this->tikTokResourceBaseCooperateDo->attributes(), ['id']), $data)->execute();
    }

    /**
     * @param TikTokResourceBaseCooperateDto $tikTokResourceBaseCooperateDto
     * @author zhuozhen
     */
    public function exportTikTokResourceBaseCooperate(TikTokResourceBaseCooperateDto $tikTokResourceBaseCooperateDto) :void
    {
        $data = $this->tikTokResourceBaseCooperateDoManager->listDataProvider($tikTokResourceBaseCooperateDto)->getModels();
        array_walk($data, static function (&$value){
            unset($value['id']);
        });
        ExcelFacade::export(array_merge([array_diff(array_values($this->tikTokResourceBaseCooperateDo->attributeLabels()),['ID'])],$data),'抖音资源库-合作情况');
    }

    /**
     * @author zhuozhen
     */
    public function exportTikTokResourceBaseCooperateExample() : void
    {
        $data = [array_diff(array_values($this->tikTokResourceBaseCooperateDo->attributeLabels()),['ID'])];
        ExcelFacade::export($data,'抖音资源库-合作情况模板');
    }


    /**
     * @param TikTokResourceBaseImport $tikTokResourceBaseImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function batchUpdateTikTokResourceBaseCooperate(TikTokResourceBaseImport $tikTokResourceBaseImport): int
    {
        $tikTokResourceBaseImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                                = ExcelFacade::import($tikTokResourceBaseImport->excelFile->tempName);
        return DbOperation::batchInsertUpdate($this->tikTokResourceBaseCooperateDo::tableName(),array_diff($this->tikTokResourceBaseCooperateDo->attributes(),['id']),$data);
    }


}