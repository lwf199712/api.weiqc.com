<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\dto;

use yii\base\Model;

/**
 * Class StatisticsUrlGroupChannelQuery
 * @package app\modules\v2\link\domain\dto
 */
class StatisticsUrlGroupChannelQuery extends Model
{
    public const SEARCH = 'search';
    /** @var int */
    public $id;
    /** @var int */
    public $page;
    /** @var int */
    public $perPage;
    /** @var string */
    public $channel_name;

    /**
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function rules(): array
    {
        return [
            [['page', 'perPage'], 'required'],
            [['page', 'perPage'], 'integer', 'integerOnly' => true],

            [['id', 'perPage', 'page'], 'integer'],
            [['channel_name'], 'string'],
        ];
    }


    public function setScenario($value)
    {
        parent::setScenario($value);
        return $this;
    }

    /**
     * @return array
     * @author: qzr
     * Date: 2019/12/5
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'channel_name' => '渠道名称',
            'is_delete' => '是否删除',
        ];
    }
}

