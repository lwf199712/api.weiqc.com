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
    /** @var int */
    public $is_delete = 0;
    /** @var string */
    public $creator;
    /** @var int */
    public $create_time;
    /** @var string */
    public $updater;
    /** @var int */
    public $update_time;

    /**
     * @return array
     * @author: qzr
     */
    public function rules(): array
    {
        return [
            [['id', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['channel_name', 'updater', 'creator'], 'string'],
        ];
    }

    /**
     * @return array
     * @author: qzr
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'channel_name' => '渠道名称',
            'is_delete' => '是否删除',
            'creator' => '创建人',
            'create_time' => '创建时间',
            'updater' => '更新者',
            'update_time' => '更新时间',
        ];
    }
}
