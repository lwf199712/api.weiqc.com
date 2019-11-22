<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;

use yii\base\Model;

/**
 * Class PhysicalSendStatusQuery
 * @package app\modules\v2\marketDept\domain\dto
 */
class PhysicalSendStatusQuery extends Model
{
    /** @var int */
    public $rp_id;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            ['rp_id', 'required'],
            [['rp_id', 'perPage', 'page'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'rp_id'     => '置换订单id',
            'perPage'   => '记录数',
            'page'      => '页数'
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