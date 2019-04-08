<?php

namespace app\modules\v1\common\exception;

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
        $this->message = $message . $this->getModelErrors($model);
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }


    /**
     * get model errors
     *
     * @param ActiveRecord $model
     * @return string
     * @author: lirong
     */
    private function getModelErrors(ActiveRecord $model): string
    {
        $errors = '';
        if (is_array($model->errors)) {
            foreach ($model->errors as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $errors .= $error;
                    }
                }
            }
        }
        return $errors;
    }

}