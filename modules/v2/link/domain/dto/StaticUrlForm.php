<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;

use Yii;
use yii\base\Model;

/**
 * Class StaticUrlForm
 * @property-read string $ident;
 * @property-read string $mId;
 * @package app\modules\v2\link\domain\dto
 */
class StaticUrlForm extends Model
{
    /** @var string */
    public $name;
    /** @var string */
    public $pattern;
    /** @var string */
    public $service;
    /** @var string */
    public $url;
    /** @var string */
    public $pcurl;
    /** @var string */
    public $group_id;
    /** @var array */
    public $service_list;
    /** @var array */
    public $conversions_list;

    public function rules()
    {
        return [
            [['pattern','url','pcurl','group_id'],'required'],
            [['name','pattern','service','url','pcurl','group_id'],'string'],
            [['service_list','conversions_list'],'safe'],
            [['url','pcurl'],'url'],
            [['name', 'url' , 'pcurl','group_id','service'], 'filter', 'filter' => 'trim'],

        ];
    }

    public function attributeLabels() : array
    {
        return [
            'name' => '链接名称	',
            'pattern' => '模式',
            'service' => '公众号',
            'ident' => 'Token',
            'url' => '移动跳转链接',
            'pcurl' => 'pc跳转链接',
            'group_id' => '分组ID',
            'service_list' => '公众号列表（模式1/2会有）',
            'conversions_list' => '公众号目标转粉数列表',
        ];
    }


    public function getIdent() : string
    {
        return uniqid('', false);
    }

    public function getMId() : int
    {
        return Yii::$app->user->getId();
    }


}