<?php

namespace app\common\exception;

use Exception;
use yii\base\Model;
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
        $this->message = $message . $this->modelErrorsArrayAsString($model);
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }


    /**
     * 获得Model的错误信息,以字符串返回
     *
     * @param Model $model
     * @return string
     * @author: lirong
     */
    public function modelErrorsArrayAsString(Model $model): string
    {
        $errorsString = [];
        if (is_array($model->errors)) {
            foreach ($model->errors as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $errorsString[] = $error;
                    }
                }
            }
        }
        return implode(',', array_unique($errorsString));
    }
}