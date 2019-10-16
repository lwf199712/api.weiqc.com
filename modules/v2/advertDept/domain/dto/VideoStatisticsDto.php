<?php
declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\dto;


use app\common\utils\TimeUtils;
use yii\base\Model;

class VideoStatisticsDto extends Model
{
    public const VIDEO_TEST_DETAIL_INDEX = 1;



    /** @var string */
    public $inputBeginTime;
    /** @var string */
    public $inputEndTime;
    /** @var string */
    public $specifiedTime;
    /** @var string */
    public $number;
    /** @var string */
    public $follower;
    /** @var string */
    public $serviceId;
    /** @var integer */
    public $videoId;
    /** @var integer */
    public $videoName;
    /** @var integer */
    public $page;
    /** @var integer */
    public $perPage;

    public function rules(): array
    {
        return [
            ['videoId', 'required', 'on' => self::VIDEO_TEST_DETAIL_INDEX],
            [['page','perPage'], 'required'],
            [['videoId', 'serviceId', 'page', 'perPage'], 'integer', 'integerOnly' => true],
            ['videoId', 'filter', 'filter' => 'intval'],
            [['inputBeginTime', 'inputEndTime', 'follower', 'number','videoName','specifiedTime'], 'string'],
            [['follower', 'number','videoName'], 'filter', 'filter' => 'trim']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'inputBeginTime' => '录入开始时间',
            'inputEndTime'   => '录入结束时间',
            'specifiedTime'  => '指定时间',
            'number'         => '大投/测试编号',
            'follower'       => '跟进人',
            'serviceId'      => '服务Id',
            'videoId'        => '视频名称Id',
            'videoName'      => '视频名称',
            'page'           => '当前页',
            'perPage'        => '每页条数',
        ];
    }

    /**
     * 获取录入开始时间
     * @return int
     * @author dengkai
     * @date   2019/9/27
     */
    public function getInputBeginTime(): int
    {
        return TimeUtils::getBeginTimeStamp($this->inputBeginTime);
    }

    /**
     * 获取录入结束时间
     * @return int
     * @author dengkai
     * @date   2019/9/27
     */
    public function getInputEndTime(): int
    {
        return TimeUtils::getEndTimeStamp($this->inputEndTime);
    }

    /**
     * 获取指定的开始及结束时间戳
     * @return array
     * @author dengkai
     * @date   2019/9/27
     */
    public function getSpecifiedTime(): array
    {
        return TimeUtils::getSpecifiedTimeStamp($this->specifiedTime);
    }
}