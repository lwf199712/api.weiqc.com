<?php


use app\common\utils\RedisUtils;
use Symfony\Component\EventDispatcher\Event;

/**
 * @property AutoConvertService $autoConvertService
 * @property RedisUtils         $redisUtils
 * @property string             $stopSupport
 * Class AutoConvertWhiteListEvent
 */
class AutoConvertWhiteListEvent extends Event implements AutoConvertEventInterface
{
    public const NAME = 'AutoConvertWhiteListEvent';

    /** @var  RedisUtils */
    public $redisUtils;
    /** @var AutoConvertService */
    public $autoConvertService;
    /** @var string */
    public $returnDept;
    /** @var string $stopSupport 是否停止供粉 */
    public $stopSupport;

    public function __construct(AutoConvertService $autoConvertService,
                                RedisUtils $redisUtils,
                                string $stopSupport
    )
    {
        $this->autoConvertService = $autoConvertService;
        $this->redisUtils         = $redisUtils;
        $this->stopSupport        = $stopSupport;
    }

    /**
     * 获取粉丝需转移到的分部
     * @return string|null
     * @author zhuozhen
     */
    public function getReturnDept(): ?string
    {
        return $this->returnDept;
    }

    /**
     * 设置粉丝需转移到的分部
     * @param string $dept
     * @author zhuozhen
     */
    public function setReturnDept(string $dept = null): void
    {
        $this->returnDept = $dept;
    }
}