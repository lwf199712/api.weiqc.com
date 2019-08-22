<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

class DeliveryVolumeHandledDto extends Model
{

    public $put_volume;

    public $conversion_cost;

    public $date;

    public $statis_url_id;

    public function rules()
    {
        return [
            [['put_volume','conversion_cost','date','statis_url_id'],'required'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'put_volume' => '投放量',
            'conversion_cost' =>'转换成本',
            'date' => '日期',
            'statis_url_id' => '统计链接ID',
        ];
    }
}