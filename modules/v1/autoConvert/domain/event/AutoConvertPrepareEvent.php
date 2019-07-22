<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\domain\event;

use app\common\utils\RedisUtils;
use app\modules\v1\autoConvert\domain\vo\ConvertRequestVo;
use app\modules\v1\autoConvert\service\AutoConvertService;
use yii\base\Event;

/**
 * Class AutoConvertPrepareEvent
 * @property ConvertRequestVo $convertRequestVo
 * @property autoConvertService $autoConvertService
 * @property RedisUtils $redisUtils
 * @property array $errors;
 * @property string $distribute;
 * @property string $stopSupport;
 * @property string $whiteList;
 * @package app\modules\v1\autoConvert\domain\event
 * @author  zzz
 */
class AutoConvertPrepareEvent extends Event
{
    protected $convertRequestVo;

    protected $autoConvertService;

    protected $redisUtils;
    /** @var string 是否可分配 */
    protected $distribute;
    /** @var string 是否停止供粉 */
    protected $stopSupport;
    /** @var string 白名单 */
    protected $whiteList;
    /** @var array 错误信息 */
    protected $errors;

    public function __construct(ConvertRequestVo $convertRequestVo, autoConvertService $autoConvertService,RedisUtils $redisUtils,$config = [])
    {
        $this->convertRequestVo = $convertRequestVo;
        $this->autoConvertService = $autoConvertService;
        $this->redisUtils = $redisUtils;
        parent::__construct($config);
    }

    public function getConvertRequestVo() : ConvertRequestVo
    {
        return $this->convertRequestVo;
    }

    public function getAutoConvertService() : autoConvertService
    {
        return $this->autoConvertService;
    }


    public function getErrors() : ?array
    {
        return $this->errors;
    }


    public function setErrors(array $errors) : void
    {
        $this->errors[] = $errors;
    }


    public function getDistribute() : string
    {
        return $this->distribute;
    }


    public function setDistribute(string $distribute): void
    {
        $this->distribute = $distribute;
    }


    public function getStopSupport() : string
    {
        return $this->stopSupport;
    }


    public function setStopSupport(string $stopSupport): void
    {
        $this->stopSupport = $stopSupport;
    }

    public function getWhiteList() :string
    {
        return $this->whiteList;
    }

    public function setWhiteList(string $whiteList): void
    {
        $this->whiteList = $whiteList;
    }


    public function getRedisUtils(): RedisUtils
    {
        return $this->redisUtils;
    }

    public function setRedisUtils(RedisUtils $redisUtils): void
    {
        $this->redisUtils = $redisUtils;
    }


}