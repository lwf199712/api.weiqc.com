<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

/**
 * Class StaticUrlVisitDetailDto
 * @package app\modules\v2\link\domain\dto
 */
class StaticUrlVisitDetailDto extends Model
{
    /** @var string  */
    public $id;
    /** @var string  */
    public $type ;
    /** @var string  */
    public $beginDate;
    /** @var string  */
    public $endDate;
    /** @var string  */
    public $field;
    /** @var string  */
    public $fieldValue;
    /** @var string  */
    public $ip;
    /** @var string  */
    public $referer;
    /** @var string  */
    public $page;
    /** @var string  */
    public $country;
    /** @var string  */
    public $area;


    public function rules()
    {
        return [
            [['beginDate'], 'compare', 'compareAttribute' => 'endDate', 'operator' => '<', 'enableClientValidation' => false],
            ['type','in','range' => ['ip','uv','pv','cv']]
        ];
    }


    public function attributeLabels()
    {
        return [
            'id'         => '统计链接ID',
            'type'       => '数据类型',
            'beginDate'  => '开始时间',
            'endDate'    => '结束时间',
            'field'      => '搜索字段',
            'fieldValue' => '搜索字段的值',
            'ip'         => 'IP',
            'referer'    => '来源',
            'page'       => '访问页面',
            'country'    => '位置',
            'area'       => '接入商',
        ];
    }
}