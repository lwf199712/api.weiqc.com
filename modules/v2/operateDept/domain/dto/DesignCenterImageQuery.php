<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;


use yii\base\Model;

/**
 * Class DesignCenterImageQuery
 * @package app\modules\v2\operateDept\domain\dto
 */
class DesignCenterImageQuery extends Model
{
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var string */
    public $name;
    /** @var string */
    public $stylist;
    /** @var int */
    public $auditStatus;

    public function rules(): array
    {
        return [
            [['beginTime', 'endTime', 'audit_status'], 'integer'],
            [['name', 'stylist'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'beginTime'   => '开始时间',
            'endTime'     => '结束时间',
            'name'        => '名称',
            'stylist'     => '设计师',
            'auditStatus' => '审核状态',
        ];
    }




}