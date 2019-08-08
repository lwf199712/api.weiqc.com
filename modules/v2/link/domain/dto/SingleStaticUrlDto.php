<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

class SingleStaticUrlDto extends Model
{
    public $id;

    public function rules() : array
    {
        return [
            ['id', 'required'],
        ];
    }

    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
        ];
    }
}