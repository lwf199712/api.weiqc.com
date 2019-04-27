<?php

namespace app\modules\v1\userAction\domain\vo;

use yii\base\Model;

/**
 * Class ConversionRequestVo
 *
 * @property string $token
 * @package app\modules\v1\conversion\domain\vo
 * @author: lirong
 */
class LinksRequestVo extends Model
{
    /* @var string $token */
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
            [['token'], 'string', 'max' => 128],
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
            'token'   => 'token',
        ];
    }
}
