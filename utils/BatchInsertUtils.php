<?php

namespace app\utils;

use Yii;
use yii\db\Exception;

/**
 * 工具箱 - 批量插入工具
 * Class ArrayTool
 *
 * @package application\components\tool
 * @author: lirong
 * @data: 2019-02-13
 */
class BatchInsertUtils
{
    /**
     * 默认批量插入一次所插入的数量
     *
     * @var int SIZE
     * @author lirong
     * @data 2019-02-18
     */
    public const SIZE = 1000;

    /**
     * 批量插入工具 - 插入更新 ON DUPLICATE KEY UPDATE
     *
     * @param array $dataList
     * @param array $fieldList
     * @param string $tableName
     * @param int $size 自定义批量插入数量
     * @throws Exception
     * @author: lirong
     * @data: 2019-02-14
     */
    public function onDuplicateKeyUpdate(array $dataList, array $fieldList, string $tableName, int $size = self::SIZE): void
    {
        $dataList = array_values($dataList);
        $dataList = array_chunk($dataList, $size);
        foreach ($dataList as &$dataChunkList) {
            $dataChunkList = array_values($dataChunkList);
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
        }
    }
}