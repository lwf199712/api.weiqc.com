<?php

namespace app\models;

use app\api\uacApi\dto\UserInfoDto;
use mdm\admin\components\UserStatus;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\filters\RateLimitInterface;
use yii\rbac\Role;
use yii\web\IdentityInterface;
use yii\web\Request;


/**
 * This is the model class for table "bm_user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $access_token
 */
class User extends ActiveRecord implements IdentityInterface,RateLimitInterface
{
    public $rateLimit = 100;

    public $allowance;

    public $allowance_updated_at;


    public static function tableName() : string
    {
        return '{{%user}}';
    }

    /**
     * 创建用户
     * @param UserInfoDto $userInfo
     * @return bool
     */
    public static function createUser(UserInfoDto $userInfo) : bool
    {
        $model = new self();
        $model->username = $userInfo->username;
        return $model->save();
    }

    /**
     * 检查用户有无分配角色
     * @param int $id
     * @return bool
     */
    public static function checkRoleExist(int $id) : bool
    {
      return  (new Query())
            ->from('{{%auth_assignment}}')
            ->where(['=','user_id',$id])
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => UserStatus::ACTIVE],
            ['status', 'in', 'range' => [UserStatus::ACTIVE, UserStatus::INACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => UserStatus::ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => UserStatus::ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => UserStatus::ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }


    /**
     * @inheritdoc
     * @throws Exception
     */
    public function behaviors():array
    {
        return [
            TimestampBehavior::class,

            //用户注册时，自动生成auth_key值
            'auth_key'     => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key',
                ],
                'value'      => Yii::$app->getSecurity()->generateRandomString(),
            ],

            //用户注册时，自动生成access_token值
            'access_token' => [
                'class'      => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'access_token',
                ],
                'value'      => static function () {
                    return Yii::$app->getSecurity()->generateRandomString(40);
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        unset(
            $fields['auth_key'],
            $fields['password_hash'],
            $fields['password_reset_token']
        );
        return $fields;
    }




    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() : string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) : string
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) : bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $password
     * @throws Exception
     * @author zhuozhen
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * 生成 "remember me" 认证key
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Returns the maximum number of allowed requests and the window size.
     * @param Request $request the current request
     * @param Action $action  the action to be executed
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     *                                  and the second element is the size of the window in seconds.
     */
    public function getRateLimit($request, $action) :array
    {
        return [$this->rateLimit, 600];
    }

    /**
     * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
     * @param Request $request          the current request
     * @param Action  $action           the action to be executed
     * @return array|void
     *                                  and the second element is the corresponding UNIX timestamp.
     */
    public function loadAllowance($request, $action) :array
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    /**
     * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
     * @param Request $request   the current request
     * @param Action $action    the action to be executed
     * @param int              $allowance the number of allowed requests remaining.
     * @param int              $timestamp the current timestamp.
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }
}
