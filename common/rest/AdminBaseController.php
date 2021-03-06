<?php
declare(strict_types=1);

namespace app\common\rest;

use app\common\exception\ApiException;
use app\components\Auth;
use stdClass;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\IntegrityException;
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
        $actions = parent::actions();
        //Unified processing of cross-domain authentication interfaces
        $actions['options'] = [
            'class' => OptionsAction::class,
        ];
        unset($actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'actionIndex'];
        return $actions;
    }


    /**
     * v2:????????????
     * @return array
     * @author zhuozhen
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return Auth::authentication($behaviors);
    }

    /**
     * v2:API????????????
     * @param string $action
     * @param null $model
     * @param array $params
     * @return bool|void
     * @throws ForbiddenHttpException
     * @author zhuozhen
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        return Auth::checkRoute($this->getUniqueId() . '/' . $action, Yii::$app->user);
    }

    /**
     * @param string $actionName
     * @return Model
     * @throws \yii\base\Exception
     * @author zhuozhen
     */
    public function dtoMap(string $actionName): Model
    {
        throw new \yii\base\Exception($actionName . 'do not in  implement dto list');
    }


//    /**
//     * This method is invoked right before an action is executed.
//     * Set request parameters in advance and transaction
//     *
//     * @param yii\base\InlineAction $action
//     * @return bool
//     * @throws BadRequestHttpException
//     * @throws \yii\base\Exception
//     * @author: zhuozhen
//     */
    /**
     * @param $action
     * @return bool
     * @throws ApiException
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws \yii\base\Exception
     * @author wenxiaomei
     * @date 2019/12/17
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action) !== true) {
            return false;
        }
        if ($this->checkAccess( Yii::$app->controller->action->id) === false){
            return false;
        }
        $this->request  = Yii::$app->request;
        $this->response = Yii::$app->response;

        $actionName = $action->actionMethod ?? 'actionIndex';
        $this->dto  = $this->dtoMap($actionName);

        if ($this->dto instanceof EmptyDto) {
            $this->transaction = Yii::$app->db->beginTransaction();
            return parent::beforeAction($action);
        }

        if (in_array($this->request->getMethod(), ['GET', 'HEAD','DELETE'])) {
            $this->dto->setAttributes($this->request->get());
        } else {
            $this->dto->setAttributes($this->request->post());
        }
        if ($this->dto->validate() === false) {
            //throw new IntegrityException('????????????????????????', $this->dto->getErrors());
            $data = $this->dto->getFirstErrors();
            $data = implode('', $data);
            throw new ApiException($data, 40001);
        }
        $this->transaction = Yii::$app->db->beginTransaction();
        return true;
    }

    /**
     * @param $action
     * @param $result
     * @return mixed
     * @throws Exception
     * @author zhuozhen
     */
    public function afterAction($action, $result)
    {
        if ($result && $result[1] !== 200 && $this->transaction->getIsActive()) {
            $this->transaction->rollBack();
        }
        if ($result && $result[1] === 200 && $this->transaction->getIsActive()) {
            $this->transaction->commit();
        }
        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }


}
