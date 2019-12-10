<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\dto;
use yii\base\Model;

/**
 * Class DesignCenterImageQuery
 * @package app\modules\v2\operateDept\domain\dto
 */
class DesignCenterImageQuery extends Model
{
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var string */
    public $name;
    /** @var string */
    public $stylist;
    /** @var string */
    public $picture_address;
    /** @var int */
    public $audit_status;
    /** @var string */
    public $size;
    /** @var string */
    public $type;
    public $category;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            [['beginTime', 'endTime', 'audit_status','perPage','page'], 'integer'],
            [['name', 'stylist', 'size','type', 'picture_address'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'beginTime'      => '开始时间',
            'endTime'        => '结束时间',
            'name'           => '名称',
            'stylist'        => '设计师',
            'audit_status'   => '审核状态',
            'size'           => '图片规格',
            'type'           => '类型',
            'category'       => '图片属性',
            'picture_address'=> '图片地址',
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
