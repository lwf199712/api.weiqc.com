<?php declare(strict_types=1);

namespace app\modules\v1\userAction\service;

use app\common\exception\RedisException;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;
use app\daemon\course\urlConvert\domain\dto\RedisUrlConvertDto;

/**
 * Interface UserActionStaticConversionService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface UserActionCache
{

    /**
     * 缓存用户行为 - 浏览(独立ip记录)
     *
     * @param RedisAddViewDto $redisAddViewDto
     * @return void
     * @throws RedisException
     * @author: lirong
     */
    public function addViews(RedisAddViewDto $redisAddViewDto): void;


    /**
     * 缓存用户行为 - 短链转长链（独立IP记录）
     * @param RedisUrlConvertDto $redisUrlConvertDto
     * @throws RedisException
     */
    public function addUrConvertHits(RedisUrlConvertDto $redisUrlConvertDto) : void;


    /**
     * 缓存用户行为 - 短链转长链（独立访客记录）
     * @param RedisUrlConvertDto $redisUrlConvertDto
     * @throws RedisException
     */
    public function addUrConvertClient(RedisUrlConvertDto $redisUrlConvertDto) : void;


    /**
     * 缓存用户行为 - 短链转长链（访问数据）
     * @param RedisUrlConvertDto $redisUrlConvertDto
     * @throws RedisException
     */
    public function addUrConvertVisit(RedisUrlConvertDto $redisUrlConvertDto) : void;
}
