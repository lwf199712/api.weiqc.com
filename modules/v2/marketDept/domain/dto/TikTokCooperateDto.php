<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;

/**
 * Class TikTokCooperateDto
 * @property int $beginTime
 * @property int $endTime
 * @package app\modules\v2\marketDept\domain\dto
 */
class TikTokCooperateDto extends Model
{

    public const SEARCH = 'search';

    //注意：编辑时前端需要传 scenario 字段，值为 以下仨个

    public const EDIT = 'edit';

    public const EDIT_IMPORT = 'edit_import';

    public const AUTHORIZE_MANAGE = 'authorize_manage';


    public $scenario;

    /** @var int */
    public $id;
    /** @var string */
    public $nickname;
    /** @var string */
    public $follow;
    /** @var string */
    public $draft_verify;
    /** @var string */
    public $final_verify;
    /** @var string */
    public $product;
    /** @var string */
    public $cooperate_pattern;
    /** @var string */
    public $dept;
    /** @var int */
    private $time;
    /** @var int */
    private $beginTime;
    /** @var int */
    private $endTime;

    public function rules(): array
    {
        return [
            ['dept','required','on' => self::SEARCH],
            [['cooperate_pattern', 'nickname', 'product', 'follow'], 'string', 'on' => self::SEARCH],
            [['id', 'draft_verify', 'final_verify'], 'integer', 'on' => self::SEARCH],
            [['final_price', 'product', 'cooperate_pattern'], 'string', 'on' => self::EDIT],
            [['id', 'video_num'], 'integer', 'on' => self::EDIT],
            [['nickname', 'channel', 'follow', 'kol_info', 'draft_price', 'link'], 'string', 'on' => self::EDIT_IMPORT],
            [['id', 'time'], 'integer', 'on' => self::EDIT_IMPORT],
            [['authorize_performance', 'authorize_time', 'authorize_remark'], 'required', 'on' => self::AUTHORIZE_MANAGE],
            [['authorize_performance', 'authorize_time', 'authorize_remark'], 'string', 'on' => self::AUTHORIZE_MANAGE],
            ['id', 'integer', 'on' => self::AUTHORIZE_MANAGE],
            ['scenario','in','range' => [ self::EDIT , self::EDIT_IMPORT ,self::AUTHORIZE_MANAGE] ,'message' => '场景值错误' ]
        ];
    }


    public function attributeLabels(): array
    {
        return [
            //-----------搜索--------------
            'nickname'              => '昵称',
            'follow'                => '跟进人',
            'draft_verify'          => '初审',
            'final_verify'          => '终审',
            'product'               => '产品',
            'cooperate_pattern'     => '合作模式',
            'dept'                  => '部门',
            //-----------编辑---------------
            'final_price'           => '最终价格',
            'video_num'             => '视频数量',
            //-----------编辑导入-----------
            'channel'               => '渠道',
            'fans_num'              => '粉丝数量',
            'time'                  => '时间',
            'kol_info'              => 'KOL具体信息',
            'draft_quotation'       => '初步报价',
            'link'                  => '链接',
            //-----------授权管理------------
            'authorize_performance' => '授权平台',
            'authorize_time'        => '授权时间',
            'authorize_remark'      => '授权备注',
        ];
    }


    public function fields() : array
    {
        switch ($this->getScenario()) {
            case self::SEARCH :
                return parent::fields();
            case self::EDIT :
                return [
                    'final_price',
                    'product',
                    'video_num',
                    'cooperate_pattern',
                ];
            case self::EDIT_IMPORT :
                return [
                    'nickname',
                    'channel',
                    'fans_num',
                    'time',
                    'kol_info',
                    'draft_quotation',
                    'link',
                ];
            case self::AUTHORIZE_MANAGE :
                return [
                    'authorize_performance',
                    'authorize_time',
                    'authorize_remark',
                ];
            default:
                return parent::fields();
        }
    }


    /**
     * @return int
     */
    public function getBeginTime(): ?int
    {
        return $this->beginTime;
    }

    /**
     * @param string $beginTime
     */
    public function setBeginTime(string $beginTime): void
    {
        $this->beginTime = strtotime($beginTime);
    }

    /**
     * @return int
     */
    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = strtotime($endTime);
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = strtotime($time);
    }


}