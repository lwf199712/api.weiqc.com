<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;
use yii\base\Model;

class DesignCenterCategoryManagementQuery extends Model
{
    /** @var string */
    public $category;
    /** @var int */
    public $type;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            [['type', 'page', 'perPage'], 'integer'],
            [['category'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'type'          => '类型',
            'category'      => '类别',
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