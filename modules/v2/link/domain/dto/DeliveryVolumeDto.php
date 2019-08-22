<?php declare(strict_types=1);


namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

class DeliveryVolumeDto extends Model
{

    public const VIEW = 'view';

    public const DELETE = 'delete';

    public const GENERATE_DATA = 'generate_data';

    public $id;

    public $static_id;

    public function setScenario($value)
    {
        parent::setScenario($value);
        return $this;
    }


    public function rules()
    {
        return [
            ['id', 'required', 'on' => [self::VIEW, self::DELETE, self::GENERATE_DATA]],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'        => 'ID',
            'static_id' => '统计链接ID',
        ];
    }
}