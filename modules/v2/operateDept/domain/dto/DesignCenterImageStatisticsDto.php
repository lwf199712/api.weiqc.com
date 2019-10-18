<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\dto;

use yii\base\Model;

class DesignCenterImageStatisticsDto extends Model
{
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var string */
    public $stylist;
    /** @var string */
    public $type;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            [['beginTime', 'endTime', 'perPage', 'page'], 'integer'],
            [['stylist', 'type'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'beginTime' => '开始时间',
            'endTime'   => '结束时间',
            'stylist'   => '设计师',
            'type'      => '类型',
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