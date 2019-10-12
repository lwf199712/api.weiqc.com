<?php declare(strict_types=1);


namespace app\api\uacApi\dto;


use yii\base\Model;

/**
 * Class UserInfoDto
 *
 * @property int $id
 * @property string $uuid
 * @property string $username
 * @property string $realName
 * @property int $groupId
 * @property string $groupName
 * @property string $avatar
 * @property string $status
 * @property string $statusCN
 *
 * @package app\api\uacApi\dto
 */
class UserInfoDto extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $uuid;
    /** @var string */
    public $username;
    /** @var string */
    public $realName;
    /** @var int */
    public $groupId;
    /** @var string */
    public $groupName;
    /** @var string */
    public $avatar;
    /** @var string */
    public $status;
    /** @var string */
    public $statusCN;


    public function rules(): array
    {
        return [
            [['id', 'groupId'], 'integer'],
            [['uuid', 'username','realName', 'groupName', 'avatar', 'status', 'statusCN'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'        => '用户ID',
            'uuid'      => '用户UUID',
            'username'  => '用户名',
            'realName'  => '姓名',
            'groupId'   => '所属群组id(部门id)',
            'groupName' => '所属群组名称(部门名称)',
            'avatar'    => '头像链接地址',
            'status'    => '用户账号状态[normal:正常,freeze:冻结]',
            'statuCN'   => '用户账号描述',
        ];
    }
}