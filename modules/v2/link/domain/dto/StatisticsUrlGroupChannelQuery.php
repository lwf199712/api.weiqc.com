<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;

use yii\base\Model;

/**
 * Class StatisticsUrlGroupChannelQuery
 * @package app\modules\v2\link\domain\dto
 */
class StatisticsUrlGroupChannelQuery extends Model
{
    /** @var int */
    public $id;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;
    /** @var int */
    public $is_delete = 0;
    /** @var string */
    public $creator;
    /** @var int */
    public $create_time;
    /** @var int */
    public $update_time;
    /** @var string */
    public $channel_name;

    /**
     * @return array
     * @author: qzr
     */
    public function rules(): array
    {
        return [
            [['id', 'perPage', 'page'], 'integer'],
            [['channel_name'], 'string'],
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

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

}
