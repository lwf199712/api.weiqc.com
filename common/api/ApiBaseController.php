<?php

namespace app\common\api;

use Yii;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\BadRequestHttpException;

/**
 * rest base controller
 * Class ConversionController
 *
 * @property Request $request The request component. This property is read-only.
 * @property Response $response The response component. This property is read-only.
 * @package app\modules\v1\rest
 * @author: lirong
 */
abstract class ApiBaseController
{
    /* @var Request $request */
    protected $request;
    /* @var Response $response */
    protected $response;

    /**
     * This method is invoked right before an action is executed.
     * Set request parameters in advance and transaction
     *
     * @author: lirong
     */
    public function __construct()
    {
        $this->request = Yii::$app->request;
        $this->response = Yii::$app->response;
    }

}