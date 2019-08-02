<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\service;


interface UserActionPageMonitorService
{
    /**
     * 批量插入页面数据
     * @param array[]PageMonitorPageVo $insertData
     * @return int
     * @author zhuozhen
     */
    public function batchInsertPageData(array $pageMonitorPageVoList) : int;

    /**
     * 批量插入模块数据
     * @param array[]PageMonitorModuleVo $insertData
     * @return int
     * @author zhuozhen
     */
    public function batchInsertPageModuleData(array $pageMonitorModuleVoList) : int ;
}