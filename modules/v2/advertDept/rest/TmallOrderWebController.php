<?php declare(strict_types=1);

namespace app\modules\v2\advertDept\rest;

use app\common\facade\ExcelFacade;
use app\common\web\WebBaseController;
use app\models\dataObject\TmallOrderDo;
use app\modules\v2\advertDept\domain\dto\TmallOrderImport;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * Class TmallOrderWebController
 * @property-read TmallOrderImport $tmallOrderImport
 * @package app\modules\v2\advertDept\rest
 */
class TmallOrderWebController extends WebBaseController
{
    /** @var TmallOrderImport */
    public $tmallOrderImport;
    /** @var TmallOrderDo */
    public $tmallOrderDo;

    public function __construct($id, $module,
                                TmallOrderImport $tmallOrderImport,
                                TmallOrderDo $tmallOrderDo,
                                $config = [])
    {
        $this->tmallOrderImport = $tmallOrderImport;
        $this->tmallOrderDo = $tmallOrderDo;
        parent::__construct($id, $module, $config);
    }


    /**
     * transaction close
     *
     * @return array
     * @author: lirong
     */
    public function transactionClose(): array
    {
        return ['actionIndex', 'actionUpload'];
    }


    /**
     * @return string
     * @throws ForbiddenHttpException
     * @author zhuozhen
     */
    public function actionIndex()
    {
        try {
            $user = Yii::$app->user->getIdentity();
            if ($user === null) {
                throw new ForbiddenHttpException();
            }
            return $this->render('@app/views/v2/tmailOrder/add.php', ['tmallOrderImport' => $this->tmallOrderImport,]);
        } catch (Throwable $exception) {
            Yii::info($exception->getMessage());
            throw new ForbiddenHttpException();
        }
    }

    /**
     * @return string
     * @throws Exception
     * @author zhuozhen
     */
    public function actionUpload()
    {
        $this->tmallOrderImport->excelFile = UploadedFile::getInstance($this->tmallOrderImport, 'excelFile');
        $data                        = ExcelFacade::import($this->tmallOrderImport->excelFile->tempName);
        foreach ($data as &$item) {
            $item['A'] = strtotime($item['A']); //A为订单创建时间
        }
        Yii::$app->db->createCommand()->batchInsert($this->tmallOrderDo::tableName(), array_diff($this->tmallOrderDo->attributes(), ['id']), $data)->execute();
        return $this->render('@app/views/v2/tmailOrder/success');

    }
}