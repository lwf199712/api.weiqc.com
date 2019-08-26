<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

class StaticUrlIntervalAnalyzeDto extends Model
{

    public const YESTERDAY = 'yesterday';

    public const SEVEN_DAY = '7day';

    public const THIRTY_DAY = '30day';

    public const MONTH = 'month';

    /** @var int */
    public $id;

    /** @var string */
    public $beginDate;
    /** @var string */
    public $endDate;

    public function rules()
    {
        return [
            ['id', 'integer'],
            [['type', 'beginDate', 'endDate'], 'string'],
            ['type', 'in', 'range' => [self::YESTERDAY, self::SEVEN_DAY, self::THIRTY_DAY, self::MONTH]],
            [['beginDate'], 'compare', 'compareAttribute' => 'endDate', 'operator' => '<', 'enableClientValidation' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'        => '统计链接ID',
            'type'      => '类型',
            'beginDate' => '开始时间',
            'endDate'   => '结束时间',
        ];
    }

    /**
     * @return int
     */
    public function getBeginDate(): int
    {
        return strtotime($this->beginDate);
    }

    /**
     * @return int
     */
    public function getEndDate(): int
    {
        return strtotime($this->endDate);
    }
}