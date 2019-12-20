<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;

use yii\base\Model;


class StatisticsServiceQuery extends Model
{
    /** @var string  */
    public const READ = 'read';
    /** @var string */
    public $account;
    /** @var string */
    public $name;
    /** @var integer */
    public $id;
    /** @var integer */
    public $prePage;
    /** @var integer */
    public $page;

    public function rules(): array
    {
        return [

            [['account', 'name'],'string'],
            [['account', 'name'], 'trim'],
            [['prePage','page', 'id'], 'integer', 'integerOnly' => true, 'min' => 1],
            [['prePage','page'], 'required', 'on' => self::READ],

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
            'prePage' => '页数',
            'page'    => '第几页',
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
