<?php
declare(strict_types=1);

namespace app\common\utils;


use Yii;
use yii\db\Exception;

class DbOperation
{
    /**
     * 批量插入数据库（如唯一键冲突，则更新其数据)
     * @param string $table
     * @param array  $property
     * @param array  $data
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public static function batchInsertUpdate(string $table,array $property = [] ,array $data) : int
    {
        if (!empty($property) && $data) {
            $property_str = '(';
            foreach ($property as $item) {
                $property_str .= $item . ',';
            }
            $property_str = substr($property_str, 0, -1);
            $property_str .= ')';

            $sql = 'insert into ' . $table;
            $sql .= $property_str . 'values';

            foreach ($data as $a => $item) {
                $sql .= '(';
                foreach ($item as $b => $value) {
                    $sql .= ':' . $b . '_' . $a . ',';
                    //  :applyDeptId_1
                }
                $sql = substr($sql, 0, -1) . '),';
            }
            $sql = substr($sql, 0, -1);
            $sql .= 'on duplicate key update ';
            $property_str = '';
            foreach ($property as $item) {
                $property_str .= $item . '=values(' . $item . '),';
            }
            $property_str = substr($property_str, 0, -1);
            $sql .= $property_str;

            $command = Yii::$app->db->createCommand($sql);
            foreach ($data as $key1 => $item) {
                $sql .= '(';
                foreach ($item as $key2 => &$value) {
                    $command->bindParam(':' . $key2 . '_' . $key1, $value);

                }
                $sql = substr($sql, 0, -1) . ')';

            }
            return $command->execute();
        }
    }
}