<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;

use yii\base\Model;

class StaticServiceForm extends Model
{
    public const UPDATE = 'update';
    /** @var string */
    public $account;
    /** @var string */
    public $name;
    /** @var integer */
    public $id;


    public function rules(): array
    {
        return [
            [['name', 'account'], 'required'],
            [['name', 'account'], 'string', 'max' => 64],
            ['id', 'required', 'on' => self::UPDATE]

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'account' => '公众号账号',
            'name'    => '公众号名称',

        ];
    }

    public function setScenario($value)
    {
        parent::setScenario($value);
        return $this;
    }
}
