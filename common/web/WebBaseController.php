<?php

namespace app\common\web;

use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\rest\OptionsAction;
use yii\rest\Serializer;
use yii\web\BadRequestHttpException;

/**
 * rest base controller
 * Class ConversionController
 *
 * @property Request $request The request component. This property is read-only.
 * @property Response $response The response component. This property is read-only.
 * @property Transaction $transaction
 * @package app\modules\v1\rest
 * @author: lirong
 */
abstract class WebBaseController extends Controller
{
    /* @var Request $request */
    protected $request;
    /* @var Response $response */
    protected $response;
    /* @var Transaction $transaction */
    protected $transaction;

    /**
     * This method is invoked right before an action is executed.
     * Set request parameters in advance and transaction
     *
     * @param yii\base\InlineAction $action
     * @return bool
     * @throws BadRequestHttpException
     * @author: lirong
     */
    public function beforeAction($action): bool
    {
        if (!in_array($action->actionMethod, $this->transactionClose(), false)) {
            $this->transaction = Yii::$app->db->beginTransaction();
        }
        $this->request = Yii::$app->request;
        $this->response = Yii::$app->response;
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
        return parent::afterAction($action, $result);
    }

}