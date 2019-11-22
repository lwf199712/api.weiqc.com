<?php
declare(strict_types=1);


namespace app\modules\v2\marketDept\domain\dto;
use yii\base\Model;

/**
 * Class PhysicalReplaceOrderDto
 * @package app\modules\v2\marketDept\domain\dto
 */
class PhysicalReplaceOrderDto extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $first_audit_opinion;
    /** @var string */
    public $final_audit_opinion;
    /** @var string */
    public $first_auditor;
    /** @var string */
    public $final_auditor;
    /** @var int */
    public $first_trial;
    /** @var int */
    public $final_judgment;
    /** @var int */
    public $prize_send_status;


    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['first_audit_opinion', 'final_audit_opinion', 'first_auditor', 'final_auditor'], 'string', 'max' => 255],
            [['first_trial', 'final_judgment'], 'in', 'range' => [0, 1, 2]],
            ['prize_send_status', 'in', 'range' => [0, 1]],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'                        => 'ID',
            'first_trial'               => '初审',
            'final_judgment'            => '终审',
            'prize_send_status'         => '奖品寄出状态',
            'first_audit_opinion'       => '初审审核意见',
            'final_audit_opinion'       => '终审审核意见',
            'first_auditor'             => '初审人',
            'final_auditor'             => '终审人',
        ];
    }
}