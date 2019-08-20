<?php declare(strict_types=1);


namespace app\modules\v2\advertDept\domain\dto;


use yii\base\Model;

/**
 * Class TmallOrderDto
 * @property-read int $start_at
 * @property-read int $end_at
 * @package app\modules\v2\advertDept\domain\dto
 */
class TmallOrderDto extends Model
{

    public function rules(): array
    {
        return [
        ];
    }



    public function attributeLabels(): array
    {
        return [
            'start_at' => '开始时间',
            'end_at'   => '结束时间',
        ];
    }

    /**
     * @return int
     */
    public function getStartAt(): int
    {
        return strtotime(date('Y-m-d')) - 3600 * 24;    //两天前
    }

    /**
     * @return int
     */
    public function getEndAt(): int
    {
        return strtotime(date('Y-m-d')) + 3600 * 24;
    }

}