<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/15
 * Time: 8:39
 */

namespace app\modules\v2\advertDept\domain\vo;


use yii\base\Model;

class WindowProjectVo extends Model
{
    /** @var int */
    public $period;
    /** @var string */
    public $consume;
    /** @var string */
    public $total_turnover;
    /** @var string */
    public $real_turnover;
    /** @var string */
    public $transaction_data;

    public function rules()
    {
        return [
          [['consume', 'total_turnover', 'real_turnover', 'transaction_data'], 'requiredByASpecial'],
            [['period'], 'integer', 'max' => 23],
            [['consume', 'total_turnover', 'real_turnover', 'transaction_data', 'period'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'consume' => '消耗',
          'total_turnover' => '总成交',
          'real_turnover' => '实时成交',
          'transaction_data' => '生意参谋成交数据',
          'period' => '时间段(0~23,[0/0-1,1/1-2...以此类推])',
        ];
    }

    public function requiredByASpecial($attribute): bool
    {
        $data = ['consume' => '消耗', 'total_turnover' => '总成交', 'real_turnover' => '实时成交', 'transaction_data' => '生意参谋成交数据', 'period' => '时间段'];
        $relus = "/^[0-9]*(\.?(\d{1,2}))$/";    //验证正整数或者小数点后一道两位数的小数
        $relusTwo = "/^(?:1?\d|2[0-3])$/";      //验证整数的值是0-23的整数
        if ($attribute!=='period'){
            if(preg_match($relus, (string)$this->getAttributes([$attribute])[$attribute]) !== 1){
                $this->addError($attribute, $data[$attribute] . '的值只能是正整数或者小数点后一道两位数的小数');
                return false;
            }
        }else{
            if(preg_match($relusTwo, (string)$this->getAttributes([$attribute])[$attribute]) !== 1){
                $this->addError($attribute, $data[$attribute] . '的值是0-23的整数');
                return false;
            }
        }
        return true;
    }
}