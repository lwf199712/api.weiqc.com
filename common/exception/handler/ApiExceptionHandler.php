<?php
declare(strict_types=1);
namespace app\common\exception\handler;

use app\common\exception\ApiException;
use Yii;
use yii\web\ErrorHandler;
use yii\web\Response;

/**
 * Class ApiExceptionHandler
 * @package app\common\exception\handler
 */
class ApiExceptionHandler extends ErrorHandler
{
    public function handleException($exception)
    {
        if ($exception instanceof ApiException) {
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            $response->data   = [
                'status' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'data'    => null
            ];
            return $response->send();
        }
        return parent::handleException($exception);
    }

}
