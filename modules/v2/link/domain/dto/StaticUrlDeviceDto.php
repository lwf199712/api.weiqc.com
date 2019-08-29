<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

/**
 * Class StaticUrlDeviceDto
 * @property int $beginDate
 * @property int $endDate
 *
 * @package app\modules\v2\link\domain\dto
 */
class StaticUrlDeviceDto extends Model
{
    public $id;
    /** @var int */
    private $beginDate;
    /** @var int */
    private $endDate;


    public function rules()
    {
        return [
            ['id', 'required'],
            [['beginDate'], 'compare', 'compareAttribute' => 'endDate', 'operator' => '<', 'enableClientValidation' => false],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id'        => '统计链接ID',
            'beginDate' => '开始时间',
            'endDate'   => '结束时间',
        ];
    }

    /**
     * @return int
     */
    public function getBeginDate(): int
    {
        return $this->beginDate;
    }

    /**
     * @param mixed $beginDate
     */
    public function setBeginDate($beginDate): void
    {
        $this->beginDate = strtotime($beginDate);
    }

    /**
     * @return int
     */
    public function getEndDate(): int
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = strtotime($endDate);
    }


}