<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;

use yii\base\Model;

/**
 * Class StatisticsUrlGroupChannelQuery
 * @package app\modules\v2\link\domain\dto
 */
class StatisticsUrlGroupChannelQuery extends Model
{
    public const SEARCH = 'search';
    /** @var int */
    public $id;
    /** @var int */
    public $page;
    /** @var int */
    public $perPage;
    /** @var string */
    public $channel_name;

    /**
     * @return array
     * @author: qzr
     */
    public function rules(): array
    {
        return [
            [['page', 'perPage'], 'required', 'on' => self::SEARCH],
            [['page', 'perPage'], 'integer', 'integerOnly' => true],

            [['id', 'perPage', 'page'], 'integer'],
            [['channel_name'], 'string'],
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
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'channel_name' => '渠道名称',
            'is_delete' => '是否删除',
        ];
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return (int)$this->page;
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
        return (int)$this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }
}

