<?php declare(strict_types=1);


namespace app\modules\v2\advertDept\domain\dto;


use yii\base\Model;

class TmallOrderDto extends Model
{

    /** @var int */
    public $start_at;
    /** @var int */
    public $end_at;

    public function rules(): array
    {
        return [
            [['start_at', 'end_at'], 'required', 'message' => '开始时间和结束时间必填'],
            [['start_at', 'end_at'], 'integer', 'message' => '时间格式错误'],
            ['start_at', 'compare', 'compareAttribute' => 'end_at', 'operator' => '<', 'enableClientValidation' => false, 'message' => '开始时间必须小于结束时间'],
        ];
    }



    public function attributeLabels(): array
    {
        return [
            'start_at' => '开始时间',
            'end_at'   => '结束时间',
        ];
    }

}