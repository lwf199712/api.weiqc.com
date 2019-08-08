<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\domain\event;

use app\common\infrastructure\service\SMS;
use app\common\utils\RedisUtils;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\service\AutoConvertSectionRealtimeMsgService;
use app\modules\v1\autoConvert\service\AutoConvertService;
use Symfony\Component\EventDispatcher\Event;

/**
 * @property ConvertRequestVo $convertRequestInfo
 * @property AutoConvertService $autoConvertService
 * @property AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService
 * @property SMS $SMS
 * @property RedisUtils $redisUtils
 * @property string $distribute
 * @property string $stopSupport
 * @property string $whiteList
 * @property int $scenarioType
 * Class AutoConvertEvent
 */
class AutoConvertEvent extends Event
{
    public const DEFAULT_SCENE = 'DefaultScene';

    public const FULL_FANS_SCENE = 'FullFansScene';

    public const FIRST_IN_FULL_FANS = 1;

    public const NEXT_IN_FULL_FANS = 2;

    /** @var ConvertRequestVo */
    public $convertRequestInfo;
    /** @var  RedisUtils */
    public $redisUtils;
    /** @var AutoConvertService */
    public $autoConvertService;
    /** @var AutoConvertSectionRealtimeMsgService */
    public $autoConvertSectionRealtimeMsgService;
    /** @var SMS */
    public $SMS;
    /** @var string */
    public $returnDept;
    /** @var bool 恢复所有自动转粉模式的链接原有的公众号 false-不恢复/true-恢复*/
    public $restoreAllLinks;
    /** @var string $distribute 是否可分配 */
    public $distribute;
    /** @var string $stopSupport 是否停止供粉 */
    public $stopSupport;
    /** @var string $whiteList 白名单 */
    public $whiteList;
    /** @var array $nodeInfo 节点信息 */
    protected $nodeInfo;
    /** @var int $scenarioType */
    protected $scenarioType;

    public function __construct(ConvertRequestVo $convertRequestInfo,
                                AutoConvertService $autoConvertService,
                                AutoConvertSectionRealtimeMsgService $autoConvertSectionRealtimeMsgService,
                                SMS $SMS,
                                RedisUtils $redisUtils,
                                string $distribute,
                                string $stopSupport,
                                string $whiteList,
                                int $scenarioType
    )
    {
        $this->convertRequestInfo = $convertRequestInfo;
        $this->autoConvertService = $autoConvertService;
        $this->autoConvertSectionRealtimeMsgService = $autoConvertSectionRealtimeMsgService;
        $this->SMS = $SMS;
        $this->redisUtils = $redisUtils;
        $this->distribute = $distribute;
        $this->stopSupport = $stopSupport;
        $this->whiteList = $whiteList;
        $this->scenarioType = $scenarioType;
        $this->restoreAllLinks = false;
    }

    /**
     * 获取粉丝需转移到的分部
     * @return string || null
     * @author         zhuozhen
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


    public function getRestoreAllLinks(): bool
    {
        return $this->restoreAllLinks;
    }

    /**
     * @param bool $restoreAllLinks
     * @author dengkai
     * @date 2019-08-07
     */
    public function setRestoreAllLinks(bool $restoreAllLinks): void
    {
        $this->restoreAllLinks = $restoreAllLinks;
    }

    /**
     * 返回调用节点信息
     * @return array
     * @author zhuozhen
     */
    public function getNodeInfo(): array
    {
        return $this->nodeInfo;
    }

    /**
     * 设置当前调用节点信息
     * @param array $nodeInfo
     * @author zhuozhen
     */
    public function setNodeInfo(array $nodeInfo): void
    {
        $this->nodeInfo = $nodeInfo;
    }

    /**
     * 设置满粉场景
     * @param int $scenarioType
     * @author zhuozhen
     */
    public function setScenarioType(int $scenarioType): void
    {
        $this->scenarioType = $scenarioType;
    }

    /**
     * 获取满粉场景
     * @return int
     * @author zhuozhen
     */
    public function getScenarioType(): int
    {
        return $this->scenarioType;
    }
}