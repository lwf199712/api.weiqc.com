<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;
use yii\base\Model;

/**
 * Class PhysicalReplaceOrderQuery
 * @package app\modules\v2\marketDept\domain\dto
 */
class PhysicalReplaceOrderQuery extends Model
{
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var int */
    public $first_trial;
    /** @var int */
    public $final_judgment;
    /** @var int */
    public $prize_send_status;
    /** @var string */
    public $brand;
    /** @var string */
    public $post_status;
    /** @var string */
    public $we_chat_id;
    /** @var string */
    public $nick_name;
    /** @var string */
    public $follower;
    /** @var string */
    public $replace_product;
    /** @var int */
    private $page;
    /** @var int */
    private $perPage;

    public function rules(): array
    {
        return [
            [['beginTime', 'endTime', 'perPage', 'page', 'first_trial', 'final_judgment', 'prize_send_status'], 'integer'],
            [['brand', 'post_status', 'we_chat_id', 'nick_name', 'follower', 'replace_product'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'beginTime'         => '开始时间',
            'endTime'           => '结束时间',
            'first_trial'       => '初审',
            'final_judgment'    => '终审',
            'prize_send_status' => '奖品寄出状态',
            'brand'             => '品牌',
            'post_status'       => '发文状态',
            'we_chat_id'        => '微信号',
            'nick_name'         => '昵称',
            'follower'          => '跟进人',
            'replace_product'   => '置换产品',
        ];
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

}