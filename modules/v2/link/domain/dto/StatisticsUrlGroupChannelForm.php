<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;

use yii\base\Model;

/**
 * Class StatisticsUrlGroupChannelForm
 * @package app\modules\v2\link\domain\dto
 */
class StatisticsUrlGroupChannelForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $channel_name;

    public const CREATE = 'create';

    public const UPDATE = 'update';

    public const DELETE = 'delete';

    /**
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['channel_name'], 'string', 'max' => 32],
            [['channel_name'], 'trim'],
            [['channel_name'], 'required', 'on' => self::CREATE],
            [['id', 'channel_name'], 'required', 'on' => self::UPDATE],
            [['id'], 'required', 'on' => self::DELETE]
        ];
    }

    public function setScenario($value)
    {
        parent::setScenario($value);
        return $this;
    }

    /**
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'channel_name' => '渠道名称',
            'deleted_at' => '删除时间',
        ];
    }
}

