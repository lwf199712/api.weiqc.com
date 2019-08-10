<?php
declare(strict_types=1);

namespace app\api\toutiaoMarketingApi\oauth\dto;


use yii\base\Model;

/**
 * Class TokenResponseDto
 * @property int $code
 * @property string $message
 * @property string $data
 * @property string $access_token
 * @property string $expires_in
 * @property string $refresh_token
 * @property string $advertiser_id
 * @property string $refresh_token_expires_in
 * @package app\api\toutiaoMarketingApi\oauth\dto
 */
class TokenResponseDto extends Model
{
    /* @var int $code */
    public $code;
    /* @var string $message */
    public $message;
    /* @var string $data */
    public $data;
    /** @var string $access_token */
    public $access_token;
    /** @var int $expires_in */
    public $expires_in;
    /** @var string $refresh_token */
    public $refresh_token;
    /** @var int $advertiser_id */
    public $advertiser_id;
    /** @var int $refresh_token_expires_in */
    public $refresh_token_expires_in;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['code', 'message', 'data', 'access_token', 'expires_in', 'refresh_token', 'refresh_token_expires_in'], 'required'],
            ['code', static function ($attribute){return (int)$attribute === 0;}]
        ];
    }

    public function setAttributes($values, $safeOnly = true)
    {
        $tempAttributes = [];
        parent::setAttributes($values, $safeOnly); // TODO: Change the autogenerated stub
        foreach ($this->attributes as $attribute){
            if (is_array($attribute)){
                array_merge($tempAttributes,$attribute);
            }
        }
        foreach ($this->attributes as $key => $attribute){
            if ($attribute === null && in_array($attribute,$tempAttributes,true)){
                $this->attributes[$key] = $tempAttributes[array_search($attribute, $tempAttributes, true)];
            }
        }
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
            'code'                     => '返回码,详见【附录-返回码】',
            'message'                  => '返回信息,详见【附录-返回码】',
            'data'                     => 'json返回值',
            'access_token'             => '用于验证权限的token',
            'expires_in'               => 'access_token剩余有效时间,单位(秒)',
            'refresh_token'            => '刷新access_token,用于获取新的access_token和refresh_token，并且刷新过期时间',
            'advertiser_id'            => '登录用户对应的广告账户ID',
            'refresh_token_expires_in' => 'refresh_token剩余有效时间,单位(秒)',
        ];
    }
}