<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;

use Yii;
use yii\base\Model;

class DesignCenterHomeVideoQuery extends Model
{
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var string */
    public $name;
    /** @var  */
    public $video;
    /** @var int */
    public $audit_status;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            [['beginTime', 'endTime', 'audit_status','perPage','page'], 'integer'],
            [['name','video'], 'string'],
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