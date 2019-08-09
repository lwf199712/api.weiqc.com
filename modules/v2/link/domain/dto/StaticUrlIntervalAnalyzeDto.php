<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

class StaticUrlIntervalAnalyzeDto extends Model
{
    /** @var string */
    public $beginDate;
    /** @var string */
    public $endDate;

    public function rules()
    {
        return [
            ['id','integer'],
            [['type','beginDate', 'endDate'], 'string'],
            [['beginDate'], 'compare', 'compareAttribute' => 'endDate', 'operator' => '<', 'enableClientValidation' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '统计链接ID',
            'type' => '类型',
            'beginDate' => '开始时间',
            'endDate' => '结束时间',
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