<?php

namespace app\api\tencentMarketingApi\oauth\domain\dto;

use yii\base\Model;

/**
 * 鉴权token表单返回
 * Class OauthDto
 *
 * @property integer $account_uin 绑定的推广帐号对应的 QQ 号
 * @property integer $account_id 绑定的推广帐号 id,有操作权限的帐号 id
 * @property string $scope_list 权限列表，若为空，则表示拥有所属应用的所有权限
 * @package app\api\tencentMarketingApi\oauth\domain\dto
 * @author: lirong
 */
class AuthorizationResponseDto extends Model
{
    /* @var integer $account_uin */
    public $account_uin;
    /* @var integer $account_id */
    public $account_id;
    /* @var string $scope_list */
    public $scope_list;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [['account_uin', 'account_id', 'scope_list'], 'safe'];
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
            'account_uin' => '绑定的推广帐号对应的 QQ 号',
            'account_id'  => '绑定的推广帐号 id,有操作权限的帐号 id',
            'scope_list'  => '权限列表，若为空，则表示拥有所属应用的所有权限',
        ];
    }
}
