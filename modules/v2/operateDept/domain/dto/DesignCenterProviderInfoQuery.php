<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\dto;

use yii\base\Model;

class DesignCenterProviderInfoQuery extends Model
{
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var string */
    public $name;
    /** @var string */
    public $flag;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            [['beginTime', 'endTime', 'perPage', 'page'], 'integer'],
            [['name','flag'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'beginTime' => '开始时间',
            'endTime'   => '结束时间',
            'name'      => '视频供应商/外包设计公司',
            'flag'      => '标识（video/outer）',
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