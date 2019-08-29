<?php

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_member".
 *
 * @property int $id
 * @property int $m_id
 * @property int $invite
 * @property int $spread
 * @property string $type
 * @property string $cate
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $realname
 * @property string $contact
 * @property string $address
 * @property string $homepage
 * @property string $sex
 * @property string $phone
 * @property string $qq
 * @property string $msn
 * @property double $money
 * @property string $alipay_realname
 * @property string $alipay_account
 * @property int $count
 * @property int $lasttime
 * @property string $lastip
 * @property int $regtime
 * @property string $regip
 * @property string $issms
 * @property double $offer
 * @property string $verify
 * @property string $islock
 * @property string $lock_msg
 * @property string $statis_status
 * @property string $cpc_status
 * @property string $cps_status
 * @property double $discount 微博主用 折扣量（小数1-0.9）
 * @property int $isevaluate 微博主用 是否已评估（0/1）
 * @property int $iscooperate 微博主用 是否合作(0/1)
 * @property string $followup 微博主用 跟进人
 * @property int $planordernum 预计派单数
 * @property int $realordernum 实际派单数
 */
class MemberDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['m_id', 'invite', 'spread', 'count', 'lasttime', 'regtime', 'isevaluate', 'iscooperate', 'planordernum', 'realordernum'], 'integer'],
            [['type', 'cate', 'sex', 'issms', 'verify', 'islock', 'statis_status', 'cpc_status', 'cps_status'], 'string'],
            [['username', 'password', 'salt', 'email'], 'required'],
            [['money', 'offer', 'discount'], 'number'],
            [['username', 'lock_msg'], 'string', 'max' => 60],
            [['password', 'followup'], 'string', 'max' => 32],
            [['salt'], 'string', 'max' => 6],
            [['email', 'address', 'homepage'], 'string', 'max' => 120],
            [['realname', 'contact', 'phone', 'qq', 'alipay_realname'], 'string', 'max' => 20],
            [['msn'], 'string', 'max' => 160],
            [['alipay_account'], 'string', 'max' => 100],
            [['lastip', 'regip'], 'string', 'max' => 15],
            [['username', 'email'], 'unique', 'targetAttribute' => ['username', 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'm_id' => 'M ID',
            'invite' => 'Invite',
            'spread' => 'Spread',
            'type' => 'Type',
            'cate' => 'Cate',
            'username' => 'Username',
            'password' => 'Password',
            'salt' => 'Salt',
            'email' => 'Email',
            'realname' => 'Realname',
            'contact' => 'Contact',
            'address' => 'Address',
            'homepage' => 'Homepage',
            'sex' => 'Sex',
            'phone' => 'Phone',
            'qq' => 'Qq',
            'msn' => 'Msn',
            'money' => 'Money',
            'alipay_realname' => 'Alipay Realname',
            'alipay_account' => 'Alipay Account',
            'count' => 'Count',
            'lasttime' => 'Lasttime',
            'lastip' => 'Lastip',
            'regtime' => 'Regtime',
            'regip' => 'Regip',
            'issms' => 'Issms',
            'offer' => 'Offer',
            'verify' => 'Verify',
            'islock' => 'Islock',
            'lock_msg' => 'Lock Msg',
            'statis_status' => 'Statis Status',
            'cpc_status' => 'Cpc Status',
            'cps_status' => 'Cps Status',
            'discount' => 'Discount',
            'isevaluate' => 'Isevaluate',
            'iscooperate' => 'Iscooperate',
            'followup' => 'Followup',
            'planordernum' => 'Planordernum',
            'realordernum' => 'Realordernum',
        ];
    }
}
