<?php

interface CalculateLackFansRateService
{
    /**
     * 计算出缺粉率最高的部门
     *
     * @param AutoConvertEvent $event
     * @param bool             $isFullFans 是否全部满粉后的重新计算
     * @return array|null ['lackFansDept' => 'xxx' , 'lackFansRate' => 'xxx' , 'availableDept' => 'xxx']. null will return  while not need change service
     * @author zhuozhen
     */
    public function calculateLackFansRate(AutoConvertEvent $event,bool $isFullFans) : ? array ;
}