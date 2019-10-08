<?php declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\aggregate;

use app\common\facade\ExcelFacade;
use app\models\dataObject\TmallOrderDo;
use app\modules\v2\advertDept\domain\dto\TmallOrderDto;
use app\modules\v2\advertDept\domain\dto\TmallOrderImport;
use app\modules\v2\advertDept\domain\repository\TmallOrderDoManager;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class TmallOrderAggregate
 * @property-read TmallOrderDoManager $tmallOrderDoManager
 * @property-read TmallOrderDo        $tmallOrderDo
 * @package app\modules\v2\advertDept\domain\aggregate
 */
class TmallOrderAggregate extends BaseObject
{
    /** @var TmallOrderDoManager */
    public $tmallOrderDoManager;
    /** @var TmallOrderDo */
    public $tmallOrderDo;

    public function __construct(
        TmallOrderDoManager $tmallOrderDoManager,
        TmallOrderDo $tmallOrderDo,
        $config = [])
    {
        $this->tmallOrderDoManager = $tmallOrderDoManager;
        $this->tmallOrderDo        = $tmallOrderDo;
        parent::__construct($config);
    }

    /**
     * @param TmallOrderDto $tmallOrderDto
     * @return array
     * @author zhuozhen
     */
    public function listTmallOrder(TmallOrderDto $tmallOrderDto): array
    {
        $data =  $this->tmallOrderDoManager->listDataProvider($tmallOrderDto)->models;
        foreach ($data as &$datum){
            $datum['phone'] = substr(hash('sha256',$datum['phone']),0,-4);
        }
        return $data;
    }

    /**
     * @param TmallOrderImport $tmallOrderImport
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function ImportTmallOrder(TmallOrderImport $tmallOrderImport): int
    {
        $tmallOrderImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        $data                        = ExcelFacade::import($tmallOrderImport->excelFile->tempName);
        foreach ($data as &$item){
            $item['A'] = strtotime($item['A']); //A为订单创建时间
        }
        return Yii::$app->db->createCommand()->batchInsert($this->tmallOrderDo::tableName(), array_diff($this->tmallOrderDo->attributes(), ['id']), $data)->execute();
    }
}