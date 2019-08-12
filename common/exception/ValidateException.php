<?php

namespace app\common\exception;

use Exception;
use yii\base\UserException;
use yii\db\ActiveRecord;

/**
 * Class ValidateException
 *
 * @package app\common\exception
 * @author: lirong
 */
class ValidateException extends UserException
{
    /**
     * ValidateException constructor.
     *
     * @param ActiveRecord $model
     * @param null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(ActiveRecord $model, $message = null, $code = 0, Exception $previous = null)
    {
        $this->message = $message . implode(',',$model->getErrors());
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }
}