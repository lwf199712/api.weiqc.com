<?php
declare(strict_types=1);

use app\common\utils\RedisUtils;
use Symfony\Component\EventDispatcher\Event;

/**
 * @property ConvertRequestVo   $convertRequestInfo
 * @property AutoConvertService $autoConvertService
 * @property RedisUtils         $redisUtils
 * @property string             $distribute
 * @property string             $stopSupport
 * @property string             $whiteList
 * Class AutoConvertEvent
 */
class AutoConvertEvent extends Event
{
    public const NAME = 'AutoConvertEvent';

    /** @var ConvertRequestVo */
    protected $convertRequestInfo;
    /** @var  RedisUtils */
    protected $redisUtils;
    /** @var AutoConvertService */
    protected $autoConvertService;
    /** @var array */
    protected $returnDept;
    /** @var string $distribute 是否可分配 */
    protected $distribute;
    /** @var string $stopSupport 是否停止供粉 */
    protected $stopSupport;
    /** @var string $whiteList 白名单 */
    protected $whiteList;

    public function __construct(ConvertRequestVo $convertRequestInfo,
                                AutoConvertService $autoConvertService,
                                RedisUtils $redisUtils,
                                string $distribute,
                                string $stopSupport,
                                string $whiteList
    )
    {
        $this->convertRequestInfo = $convertRequestInfo;
        $this->autoConvertService = $autoConvertService;
        $this->redisUtils         = $redisUtils;
        $this->distribute         = $distribute;
        $this->stopSupport        = $stopSupport;
        $this->whiteList          = $whiteList;
    }

    /**
     * 获取粉丝需转移到的分部
     * @return array
     * @author zhuozhen
     */
    public function getReturnDept(): array
    {
        return $this->returnDept;
    }

    /**
     * 设置粉丝需转移到的分部
     * @param array $returnDept
     * @author zhuozhen
     */
    public function setReturnDept(array $returnDept): void
    {
        $this->returnDept = $returnDept;
    }


}