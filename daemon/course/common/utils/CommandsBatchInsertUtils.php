<?php

namespace app\daemon\course\common\utils;

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
     * @param array $dataList
     * @param array $fieldList
     * @param string $tableName
     * @return int
     * @throws Exception
     * @author: lirong
     */
    public function onDuplicateKeyUpdate(array $dataList, array $fieldList, string $tableName): int
    {
        $dataChunkList = array_values($dataList);
        $sql = 'INSERT';
        $sql .= ' INTO ' . $tableName . '(`' . implode('`,`', $fieldList) . '`) values';
        foreach ($dataChunkList as $key => $data) {
            $sql .= '(';
            foreach ($fieldList as $field) {
                if (!isset($data[$field])) {
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
        foreach ($dataChunkList as $key => $data) {
            foreach ($fieldList as $field) {
                $command->bindParam(":{$field}_{$key}", $data[$field]);
            }
        }
        $command->execute();
        return Yii::$app->db->getMasterPdo()->lastInsertId();
    }
}