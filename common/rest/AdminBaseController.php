<?php
declare(strict_types=1);

namespace app\common\rest;


use app\components\Auth;
use Yii;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;
use yii\rest\Serializer;
use yii\web\ForbiddenHttpException;

class AdminBaseController extends ActiveController
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
}