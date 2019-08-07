<?php
declare(strict_types=1);

namespace app\common\rest;

use app\components\Auth;
use http\Exception\InvalidArgumentException;
use stdClass;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Transaction;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;
use yii\rest\Serializer;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * Class AdminBaseController
 * @property Request $request The request component. This property is read-only.
 * @property Response $response The response component. This property is read-only.
 * @property Transaction $transaction
 * @property ActiveRecord $dto
 * @package app\common\rest
 */
abstract class AdminBaseController extends ActiveController
{

    /**
     * the model class name. This property must be set.
     * All are not set
     *
     * @var string
     * @author: lirong
     */
    public $modelClass = '';

    /** @var ActiveRecord $dto */
    public $dto;

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
        $actions['index']['prepareDataProvider'] = [$this, 'actionIndex'];
        return $parent;
    }



    /**
     * v2:重写行为
     * @return array
     * @author zhuozhen
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return Auth::authentication($behaviors);
    }

    /**
     * v2:API授权认证
     * @param string $action
     * @param null   $model
     * @param array  $params
     * @return bool|void
     * @throws ForbiddenHttpException
     * @author zhuozhen
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        return Auth::checkRoute($this->getUniqueId() . '/' . $action, Yii::$app->user);
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
        $this->request = Yii::$app->request;
        $this->response = Yii::$app->response;

        if (in_array($this->request->getMethod(),['GET', 'HEAD', 'OPTIONS'])){
            $this->dto->setAttributes($this->request->get());
        }else{
            $this->dto->setAttributes($this->request->post());
        }
        if ($this->dto->validate() === false) {
            throw new InvalidArgumentException($this->dto->getErrors());
        }
        return parent::beforeAction($action);
    }
}