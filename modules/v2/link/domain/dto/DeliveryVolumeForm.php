<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

/**
 * Class DeliveryVolumeForm
 * @property int $date
 * @package app\modules\v2\link\domain\dto
 */
class DeliveryVolumeForm extends Model
{
    public const UPDATE = 'update';

    /** @var int */
    public $id;
    /** @var int */
    public $static_id;
    /** @var int */
    private $date;
    /** @var */
    public $volume;


    public function setScenario($value)
    {
        parent::setScenario($value); // TODO: Change the autogenerated stub
        return $this;
    }


    public function rules(): array
    {
        return [
            ['id','required','on' => self::UPDATE],
            [['static_id', 'date', 'volume'], 'required'],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'        => 'ID',
            'static_id' => '统计链接ID',
            'date'      => '日期',
            'volume'    => '投放量',
        ];
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = strtotime($date);
    }
}