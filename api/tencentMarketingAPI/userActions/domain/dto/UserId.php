<?php


namespace app\api\tencentMarketingApi\userActions\domain\dto;

use yii\base\Model;

/**
 * Class UserId
 *
 * @property string $hash_imei IMEI 设备号保持小写，进行 md5 编码
 * @property string $hash_idfa IDFA 设备号保持大写，进行 MD5 编码
 * @property string $gdt_openid GDT Cookie Mapping 分配的 openid，不做处理
 * @property string $hash_phone 电话号码直接 MD5 编码，如 md5(13500000000)
 * @package app\api\tencentMarketingApi\userActions\domain\dto
 * @author: lirong
 */
class UserId extends Model
{
    /* @var string $hash_imei */
    public $hash_imei;
    /* @var string $hash_idfa */
    public $hash_idfa;
    /* @var string $gdt_openid */
    public $gdt_openid;
    /* @var string $hash_phone */
    public $hash_phone;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['hash_imei', 'hash_idfa', 'hash_phone'], 'string', 'max' => 32],
            [['gdt_openid'], 'string', 'min' => 1, 'max' => 64],
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
            'hash_imei'  => 'IMEI 设备号',
            'hash_idfa'  => 'IDFA 设备号',
            'gdt_openid' => 'GDT Cookie Mapping 分配的 openid',
            'hash_phone' => '电话号码直接 MD5 编码',
        ];
    }
}