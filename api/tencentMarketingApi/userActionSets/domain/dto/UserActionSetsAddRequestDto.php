<?php

namespace app\api\tencentMarketingApi\userActionSets\domain\dto;

use yii\base\Model;

/**
 * Class UserActionSetsAddRequestDto
 *
 * @property integer $account_id 推广帐号 id，有操作权限的帐号 id，包括代理商和广告主帐号 id
 * @property string $type 用户行为源类型
 * @property integer $mobile_app_id 应用 id，IOS：App Store id ； ANDROID：应用宝 id，type=ANDROID 或 IOS 时必填
 * @property string $name 用户行为源名称，当 type=WEB 时必填，当 type=ANDROID 或 IOS 时，若未填写该字段，则默认通过 mobile_app_id 获取名称
 * @property string $description 用户行为源描述
 * @package app\api\tencentMarketingApi\userActionSets\domain\dto
 * @author: lirong
 */
class UserActionSetsAddRequestDto extends Model
{
    /* @var integer $account_id */
    public $account_id;
    /* @var string $type */
    public $type;
    /* @var integer $mobile_app_id */
    public $mobile_app_id;
    /* @var string $name */
    public $name;
    /* @var string $description */
    public $description;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['account_id', 'type', 'mobile_app_id', 'name', 'description'], 'safe'],
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
            'account_id'    => '推广帐号 id',
            'type'          => '用户行为源类型',
            'mobile_app_id' => '应用 id',
            'name'          => '用户行为源名称',
            'description'   => '用户行为源描述',
        ];
    }
}
