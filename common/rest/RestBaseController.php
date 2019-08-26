<?php

namespace app\common\rest;

use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\web\Request;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;
use yii\rest\Serializer;
use yii\web\BadRequestHttpException;

/**
 * rest base controller
 * Class ConversionController
 *
 * @property Request     $request  The request component. This property is read-only.
 * @property Response    $response The response component. This property is read-only.
 * @property Transaction $transaction
 * @package app\modules\v1\rest
 * @author  : lirong
 */
abstract class RestBaseController extends ActiveController
{
    /**
     * the model class name. This property must be set.
     * All are not set
     *
     * @var string
     * @author: lirong
     */
    public $modelClass = '';
    /**
     * the configuration for creating the serializer that formats the response data.
     *
     * @var array
     * @author: lirong
     */
    public $serializer = [
        'class'              => Serializer::class,
        'collectionEnvelope' => 'items',
    ];
    /* @var Request $request */
    protected $request;
    /* @var Response $response */
    protected $response;
    /* @var Transaction $transaction */
    protected $transaction;

    /**
     * Declares external actions for the controller.
     *
     * @return mixed|array
     * @author: lirong
     */
    public function actions()
    {
        $actions = parent::actions();
        //Unified processing of cross-domain authentication interfaces
        $actions['options'] = [
            'class' => OptionsAction::class,
        ];
        unset($actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

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
        $this->request  = Yii::$app->request;
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
     * @param mixed                 $result the action return result.
     * @return mixed the processed action result.
     * @throws Exception
     * @author: lirong
     */
    public function afterAction($action, $result)
    {
        if (!in_array($action->actionMethod, $this->transactionClose(), false)) {
            //indicating whether this transaction is active
            if ($result && is_array($result) && $result[1] !== 200 && $this->transaction->getIsActive()) {
                $this->transaction->rollBack();
            }
            if ($result && is_object($result) && $result->statusCode !== 200 && $this->transaction->getIsActive()) {
                $this->transaction->rollBack();
            }
            if ($result && is_array($result) && $result[1] === 200 && $this->transaction->getIsActive()) {
                $this->transaction->commit();
            }
            if ($result && is_object($result) && $result->statusCode === 200 && $this->transaction->getIsActive()) {
                $this->transaction->commit();
            }
        }
        return parent::afterAction($action, $result);
    }

}