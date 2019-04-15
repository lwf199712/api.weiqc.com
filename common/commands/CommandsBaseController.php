<?php

namespace app\common\commands;

use Yii;
use yii\base\Action;
use yii\db\Exception;
use yii\db\Transaction;
use yii\console\Controller;
use yii\web\Request;
use yii\web\Response;

/**
 * commands base controller
 * Class ConversionController
 *
 * @property Request $request The request component. This property is read-only.
 * @property Response $response The response component. This property is read-only.
 * @property Transaction $transaction
 * @package app\modules\v1\rest
 * @author: lirong
 */
abstract class CommandsBaseController extends Controller
{
    /**
     * This method is invoked right before an action is executed.
     * Set request parameters in advance and transaction
     *
     * @param yii\base\Action $action
     * @return bool
     * @author: lirong
     */
    public function beforeAction($action): bool
    {
        $this->transaction = Yii::$app->db->beginTransaction();
        $this->request = Yii::$app->request;
        $this->response = Yii::$app->response;
        return parent::beforeAction($action);
    }

    /**
     * This method is invoked right after an action is executed.
     * commit transaction
     *
     * @param Action $action the action just executed.
     * @param mixed $result the action return result.
     * @return mixed the processed action result.
     * @throws Exception
     * @author: lirong
     */
    public function afterAction($action, $result)
    {
        //indicating whether this transaction is active
        if ($result && current($result) === false && $this->transaction->getIsActive()) {
            $this->transaction->rollBack();
        }
        if ($result && current($result) === true && $this->transaction->getIsActive()) {
            $this->transaction->commit();
        }
        return parent::afterAction($action, $result);
    }

}