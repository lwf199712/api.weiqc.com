<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;

use yii\base\Model;


class StatisticsServiceForm extends Model
{
    public const UPDATE = 'update';
    public const CREATE = 'create';
    public const DELETE = 'delete';
    /** @var string */
    public $account;
    /** @var string */
    public $name;
    /** @var integer */
    public $id;

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/12
     */
    public function rules(): array
    {
        return [
            [['name', 'account'], 'required', 'on' => self::CREATE],
            [['name', 'account'], 'trim'],
            [['name', 'account'], 'string', 'max' => 64],
            ['id', 'required', 'on' => [self::UPDATE, self::DELETE]],

        ];
    }

    /**
     * @return array
     * @author wenxiaomei
     * @date 2019/12/12
     */
    public function attributeLabels(): array
    {
        return [
            'account' => '公众号账号',
            'name'    => '公众号名称',

        ];
    }

    /**
     * @param string $value
     * @return $this|void
     * @author wenxiaomei
     * @date 2019/12/12
     */
    public function setScenario($value)
    {
        parent::setScenario($value);
        return $this;
    }
}
