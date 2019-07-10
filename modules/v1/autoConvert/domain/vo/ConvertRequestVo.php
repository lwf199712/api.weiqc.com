<?php
namespace app\modules\v1\autoConvert\vo;

use yii\base\Model;

/**
 * @property string $department
 * @property string $fansCount
 * Class convertRequestVo
 */
class ConvertRequestVo extends Model
{
    /* @var string $department */
    public $department;
    /* @var string $fansCount  */
    public $fansCount;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['department', 'fansCount'], 'string', 'max' => 128],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'department'   => '分部',
            'fansCount' => '粉丝序号',
        ];
    }
}