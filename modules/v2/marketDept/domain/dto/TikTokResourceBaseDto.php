<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;

class TikTokResourceBaseDto extends Model
{
    /** @var string */
    public $id;
    /** @var string */
    public $mcn_company_name;
    /** @var string */
    public $identity;
    /** @var string */
    public $follow;
    /** @var string */
    public $update_at_start;
    /** @var string */
    public $update_at_end;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','mcn_company_name','identity','follow'],'string'],
            [['update_at_start'], 'compare', 'compareAttribute' => 'update_at_end', 'operator' => '<', 'enableClientValidation' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mcn_company_name' => '机构/公司名称',
            'identity' => '身份',
            'follow' => '跟进人',
            'update_at_start' => '更新时间(开始)',
            'update_at_end' => '更新时间(结束)',
        ];
    }
}