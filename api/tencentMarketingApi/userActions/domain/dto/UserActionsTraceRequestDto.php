<?php

namespace app\api\tencentMarketingApi\userActions\domain\dto;

use yii\base\Model;

/**
 * Class UserActionsTraceRequestDto
 *
 * @property string $click_id 点击 id 落地页URL中的click_id，对于广点通流量为URL中的qz_gdt，对于微信流量为URL中的gdt_vid
 * @package app\api\tencentMarketingApi\domain\dto
 * @author: lirong
 */
class UserActionsTraceRequestDto extends Model
{
    /* @var string $click_id */
    public $click_id;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['click_id'], 'string', 'min' => 1, 'max' => 64],
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
            'click_id' => '点击id',
        ];
    }
}
