<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;


use yii\base\Model;

/**
 * Class StaticUrlDto
 * @property string $recycle
 * @property string $beginDate
 * @property string $endDate
 * @property string $field
 * @property string $fieldValue
 * @property string $userName
 * @property string $channelName
 * @property string $service
 * @property string $firstGroup
 * @property string $secondGroup
 * @property string $secondGroupName
 * @package app\modules\v2\link\domain\dto
 */
class StaticUrlDto extends Model
{
    /** @var string */
    public $recycle;
    /** @var string */
    public $beginDate;
    /** @var string */
    public $endDate;
    /** @var string */
    public $field;
    /** @var string */
    public $fieldValue;
    /** @var string */
    public $userName;
    /** @var string */
    public $channelName;
    /** @var string */
    public $service;
    /** @var string */
    public $firstGroup;
    /** @var string */
    public $secondGroup;
    /** @var string */
    public $secondGroupName;

    public function rules()
    {
        return [
            [['recycle', 'beginDate', 'endDate', 'field'], 'required'],
            [['beginDate', 'endDate'], 'date'],
            [['beginDate'], 'compare', 'compareAttribute' => 'endDate', 'operator' => '<', 'enableClientValidation' => false],
            ['fieldValue', 'filter', 'filter' => 'trim'],
            ['field', 'in', 'range' => ['name', 'ident', 'url', 'username']],
            ['recycle','in','range' => ['Y','N']]
        ];
    }

    public function attributes()
    {
        return [
            'recycle'         => '正常链接(N)/回收站链接(Y)',
            'beginDate'       => '查询开始时间',
            'endDate'         => '查询结束时间',
            'field'           => '搜索字段',
            'fieldValue'      => '搜索字段的值',
            'userName'        => '负责人',
            'channelName'     => '渠道',
            'firstGroup'      => '一级组别',
            'secondGroup'     => '二级组别',
            'secondGroupName' => '二级组别名',
        ];
    }

    public function getBeginDate()
    {
        return strtotime($this->beginDate ?? date('Y-m-d'));
    }

    public function getEndDate()
    {
        return strtotime($this->endDate ?? date('Y-m-d',strtotime('+1 day')) ) ;
    }


}