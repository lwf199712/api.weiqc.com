<?php


namespace app\modules\v1\userAction\domain\vo;

use yii\base\Model;


/**
 * Class UrlConvertRequestVo
 * @property string $token token
 * @package app\modules\v1\userAction\domain\vo
 */
class UrlConvertRequestVo extends Model
{

    /* @var string token code */
    public $token;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [[ 'token'], 'string', 'max' => 128],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'token' => 'token',
        ];
    }

}