<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\service\impl;

use app\models\dataObject\PageMonitorModuleDo;
use app\models\dataObject\PageMonitorPageDo;
use app\modules\v1\userAction\domain\vo\PageMonitorModuleVo;
use app\modules\v1\userAction\domain\vo\PageMonitorPageVo;
use app\modules\v1\userAction\service\UserActionPageMonitorService;
use Yii;
use yii\base\InvalidConfigException;

class UserActionPageMonitorImpl implements UserActionPageMonitorService
{
    /**
     * 批量插入页面数据
     * @param array $pageMonitorPageVoList
     * @return int
     * @throws InvalidConfigException
     * @author zhuozhen
     */
    public function batchInsertPageData(array $pageMonitorPageVoList): int
    {
        $insertData = [];
        /** @var PageMonitorPageVo $pageMonitorPageVo */
        foreach ($pageMonitorPageVoList as $pageMonitorPageVo){
            $insertData[] = $pageMonitorPageVo->attributes;
        }
        Yii::$app->db->createCommand()->batchInsert(PageMonitorPageDo::tableName(), array_diff(PageMonitorPageDo::getTableSchema()->columns,['id']), $insertData);
}

    /**
     * 批量插入模块数据
     * @param array[]PageMonitorModuleVo $insertData
     * @return int
     * @throws InvalidConfigException
     * @author zhuozhen
     */
    public function batchInsertPageModuleData(array $pageMonitorModuleVoList): int
    {
        $insertData = [];
        /** @var PageMonitorModuleVo $pageMonitorModuleVo */
        foreach ($pageMonitorModuleVoList as $pageMonitorModuleVo){
            $insertData[] = $pageMonitorModuleVo->attributes;
        }
        Yii::$app->db->createCommand()->batchInsert(PageMonitorPageDo::tableName(), array_diff(PageMonitorModuleDo::getTableSchema()->columns,['id']), $insertData);
    }
}