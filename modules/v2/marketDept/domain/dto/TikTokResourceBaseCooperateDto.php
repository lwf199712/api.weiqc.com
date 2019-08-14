<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;

/**
 * Class TikTokResourceBaseCooperateDto
 * @property int    $id
 * @property int    $resource_base_id
 * @property string $kol_name
 * @property string $account_type
 * @property string $cooperate_info
 * @property string $account_id
 * @package app\modules\v2\marketDept\domain\dto
 */
class TikTokResourceBaseCooperateDto extends Model
{
    /** @var int */
    public $id;
    /** @var int */
    public $resource_base_id;
    /** @var string */
    public $kol_name;
    /** @var string */
    public $account_type;
    /** @var string */
    public $cooperate_info;
    /** @var string */
    public $account_id;


    public function rules()
    {
        return [
            [['id', 'resource_base_id'], 'integer'],
            [['kol_name', 'account_type', 'cooperate_info', 'account_id'], 'string'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'resource_base_id' => '抖音资源库ID',
            'kol_name'         => 'KOL昵称',
            'account_type'     => '账号类型',
            'cooperate_info'   => '合作情况',
            'account_id'       => '账号ID',

        ];
    }
}