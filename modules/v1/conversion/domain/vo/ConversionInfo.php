<?php

namespace app\modules\v1\conversion\domain\vo;

use yii\base\Model;

/**
 * Class ConversionInfo
 *
 * @property string $wxh 微信服务号
 * @property string $token token
 * @package app\modules\v1\domain\vo
 * @author: lirong
 */
class ConversionInfo extends Model
{
    /* @var string wxh */
    public $wxh;
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
            [['wxh', 'token'], 'string', 'max' => 128],
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
            'wxh'   => '微信服务号',
            'token' => 'token',
        ];
    }
}
