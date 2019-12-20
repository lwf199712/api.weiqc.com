<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;
use yii\base\Model;

/**
 * Class DesignCenterProviderInfoForm
 * @package app\modules\v2\operateDept\domain\dto
 */
class DesignCenterProviderInfoForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $quoted_price;
    /** @var string */
    public $site;
    /** @var string */
    public $recommended_reason;
    /** @var string */
    public $contact_way;
    /** @var string */
    public $remark;
    /** @var string */
    public $reference_case;
    /** @var string */
    public $flag;


    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['name', 'quoted_price', 'site', 'recommended_reason', 'contact_way', 'remark', 'reference_case', 'flag'], 'string'],
            [['name', 'quoted_price', 'site', 'recommended_reason', 'contact_way'], 'required'],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'                 => 'ID',
            'name'               => '名称',
            'quoted_price'       => '报价',
            'site'               => '地点',
            'recommended_reason' => '推荐理由',
            'contact_way'        => '联系方式',
            'remark'             => '备注',
            'reference_case'     => '参考案例',
            'flag'               => '标识（video/outer）',
        ];
    }
}