<?php
declare(strict_types=1);

namespace app\common\infrastructure\dto;


use yii\base\Model;

/**
 * Class MessageBundleDto
 * @property string $text
 * @property string $phone
 * @property string $template
 * @package app\common\infrastructure
 */
class MessageBundleDto extends Model
{
    /** @var string $text */
    public $text;
    /** @var string $phone */
    public $phone;
    /** @var string $template */
    public $template;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['phone', 'text', 'template'], 'required'],
            [['phone', 'text', 'template'], 'string', 'min' => 1, 'max' => 256],
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
            'text'  => '短信内容',
            'phone' => '收信人手机',
            'template' => '使用模板',
        ];
    }
}