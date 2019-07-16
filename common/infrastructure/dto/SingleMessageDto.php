<?php
declare(strict_types=1);

namespace app\common\infrastructure\dto;

use yii\base\Model;

/**
 * Class SingleMessageDto
 * @property string $text
 * @property string $mobile
 * @property string $apikey
 * @package app\common\infrastructure
 */
class SingleMessageDto extends Model
{
    /** @var string $text */
    public $text;
    /** @var string $mobile */
    public $mobile;
    /** @var string $apikey */
    public $apikey;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: zhuozhen
     */
    public function rules(): array
    {
        return [
            [['mobile', 'text', 'apikey'], 'required'],
            [['mobile', 'text' , 'apikey'], 'string', 'min' => 1, 'max' => 256],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: zhuozhen
     */
    public function attributeLabels(): array
    {
        return [
            'text'   => '短信内容',
            'mobile' => '收信人手机',
            'apikey' => '用户唯一标识',
        ];
    }
}