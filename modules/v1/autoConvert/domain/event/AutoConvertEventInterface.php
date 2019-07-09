<?php
declare(strict_types=1);

/**
 * Interface AutoConvertEventInterface
 */
interface AutoConvertEventInterface
{

    /**
     * 获取粉丝需转移到的分部
     * @return string|null
     * @author zhuozhen
     */
    public function getReturnDept(): ?string;

    /**
     * 设置粉丝需转移到的分部
     * @param string $dept
     * @author zhuozhen
     */
    public function setReturnDept(string $dept = null): void;
}