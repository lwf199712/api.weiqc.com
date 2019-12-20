<?php
declare(strict_types =1);
namespace app\common\exception;


use Exception;
use Throwable;
use Yii;

class ApiException extends Exception
{
    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param int $httpCode
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, int $httpCode = 422, Throwable $previous = null)
    {
        Yii::$app->response->setStatusCode($httpCode);
        parent::__construct($message, $code, $previous);
    }
}
