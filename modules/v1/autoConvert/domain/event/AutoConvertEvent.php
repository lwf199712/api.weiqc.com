<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\event;

use app\common\utils\RedisUtils;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\vo\ConvertRequestVo;
use Symfony\Contracts\EventDispatcher\Event;

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
    public const DEFAULT_SCENE = 'DefaultScene';

    public const FULL_FANS_SCENE = 'FullFansScene';

    /** @var ConvertRequestVo */
    public $convertRequestInfo;
    /** @var  RedisUtils */
    public $redisUtils;
    /** @var AutoConvertService */
    public $autoConvertService;
    /** @var string */
    public $returnDept;
    /** @var string $distribute 是否可分配 */
    public $distribute;
    /** @var string $stopSupport 是否停止供粉 */
    public $stopSupport;
    /** @var string $whiteList 白名单 */
    public $whiteList;

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
     * @return string || null
     * @author zhuozhen
     */
    public function getReturnDept() :? string
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