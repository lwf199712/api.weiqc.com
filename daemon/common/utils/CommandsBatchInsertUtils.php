<?php

namespace app\daemon\common\utils;

use Yii;
use yii\db\Exception;

/**
 * Class CommandsBatchInsertUtils
 *
 * @package app\commands\conversion\utils
 * @author: lirong
 */
class CommandsBatchInsertUtils
{
    /**
     * 批量插入工具 - 插入更新 ON DUPLICATE KEY UPDATE
     *
     * @param array $dataObjectList
     * @param array $fieldList
     * @param string $tableName
     * @return int
     * @throws Exception
     * @author: lirong
     */
    public function onDuplicateKeyUpdate(array $dataObjectList, array $fieldList, string $tableName): int
    {
        if (!$dataObjectList) {
            return 0;
        }
        $dataObjectList = array_values($dataObjectList);
        $sql = 'INSERT';
        $sql .= ' INTO ' . $tableName . '(`' . implode('`,`', $fieldList) . '`) values';
        foreach ($dataObjectList as $key => $dataObject) {
            $sql .= '(';
            foreach ($fieldList as $field) {
                if (!isset($dataObject[$field])) {
                    throw new Exception("批量插入失败!传入的数据中不存在字段{$field}!", [], 500);
                }
                $sql .= ":{$field}_{$key},";
            }
            $sql = substr($sql, 0, -1) . '),';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ' ON DUPLICATE KEY UPDATE ';
        foreach ($fieldList as $field) {
            $sql .= "`{$field}`=values(`{$field}`),";
        }
        $sql = substr($sql, 0, -1);
        $command = Yii::$app->db->createCommand($sql);
        //参数绑定
        foreach ($dataObjectList as $key => $dataObject) {
            foreach ($fieldList as $field) {
                $value = $dataObject[$field];
                $command->bindParam(":{$field}_{$key}", $value);
            }
        }
        $command->execute();
        return Yii::$app->db->getMasterPdo()->lastInsertId();
    }
}