<?php

namespace app\common\commands;

use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\console\Controller;

/**
 * commands base controller
 * Class ConversionController
 *
 * @property Transaction $transaction
 * @package app\common\commands
 * @author: lirong
 */
abstract class CommandsBaseController extends Controller
{
    /* @var Transaction $transaction */
    protected $transaction;

    /**
     * This method is invoked right before an action is executed.
     * Set request parameters in advance and transaction
     *
     * @param yii\base\InlineAction $action
     * @return bool
     * @author: lirong
     */
    public function beforeAction($action): bool
    {
        if (!in_array($action->actionMethod, $this->transactionClose(), false)) {
            $this->transaction = Yii::$app->db->beginTransaction();
        }
        return parent::beforeAction($action);
    }

    /**
     * transaction close
     *
     * @return array
     * @author: lirong
     */
    protected function transactionClose(): array
    {
        return [];
    }

    /**
     * This method is invoked right after an action is executed.
     * commit transaction
     *
     * @param yii\base\InlineAction $action the action just executed.
     * @param mixed $result the action return result.
     * @return mixed the processed action result.
     * @throws Exception
     * @author: lirong
     */
    public function afterAction($action, $result)
    {
        if (!in_array($action->actionMethod, $this->transactionClose(), false)) {
            //indicating whether this transaction is active
            if ($result && current($result) === false && $this->transaction->getIsActive()) {
                $this->transaction->rollBack();
            }
            if ($result && current($result) === true && $this->transaction->getIsActive()) {
                $this->transaction->commit();
            }
        }
        if (isset($result[1]) && is_string($result[1])) {
            echo $result[1];
        }
        return parent::afterAction($action, $result);
    }

}