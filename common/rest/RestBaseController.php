<?php

namespace app\common\rest;

use Yii;
use yii\base\Action;
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
 * Class RestController
 *
 * @property Request $request The request component. This property is read-only.
 * @property Response $response The response component. This property is read-only.
 * @property Transaction $transaction
 * @package app\modules\v1\rest
 * @author: lirong
 */
abstract class RestBaseController extends ActiveController
{
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
        $parent = parent::actions();
        //Unified processing of cross-domain authentication interfaces
        $parent['options'] = [
            'class' => OptionsAction::class
        ];
        unset($parent['create'], $parent['view'], $parent['update'], $parent['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $parent;
    }

    /**
     * This method is invoked right before an action is executed.
     * Set request parameters in advance and transaction
     *
     * @param yii\base\Action $action
     * @return bool
     * @throws BadRequestHttpException
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
        if (current($result) === false && $this->transaction->getIsActive()) {
            $this->transaction->rollBack();
        }
        if (current($result) === true && $this->transaction->getIsActive()) {
            $this->transaction->commit();
        }
        return parent::afterAction($action, $result);
    }

}