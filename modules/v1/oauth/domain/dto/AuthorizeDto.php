<?php

namespace app\modules\v1\oauth\domain\dto;

use yii\base\Model;

/**
 * 鉴权表单
 * https://developers.e.qq.com/oauth/authorize
 * Class AuthorizeDto
 *
 * @property integer $client_id 应用 id，在开发者官网创建应用后获得，可通过 [应用程序管理页面] 查看
 * @property string $redirect_uri 应用回调地址，仅支持 http 和 https，不支持指定端口号，且主域名必须与创建应用时登记的回调域名一致，若地址携带参数，需要对地址进行 urlencode
 * @property string $state 验证请求有效性参数，值为用户自取，用于阻止跨站请求伪造攻击
 * @property string $scope 授权范围，可选值： ads_management （广告投放）、 ads_insights （数据洞察）、 account_management （帐号服务）、 audience_management （人群管理）、 user_actions （用户行为数据接入）
 * @package app\modules\v1\oauth\domain\dto
 * @author: lirong
 */
class AuthorizeDto extends Model
{
    /* @var integer $client_id */
    public $client_id;
    /* @var string $redirect_uri */
    public $redirect_uri;
    /* @var string $state */
    public $state;
    /* @var string $scope */
    public $scope;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['client_id'], 'integer'],
            [['redirect_uri'], 'string', 'min' => 1, 'max' => 1024],
            [['state'], 'string', 'min' => 0, 'max' => 64],
            [['scope'], 'string', 'min' => 1, 'max' => 64]
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
            'client_id'    => '应用 id',
            'redirect_uri' => '应用回调地址',
            'state'        => '验证请求有效性参数',
            'scope'        => '授权范围',
        ];
    }
}
