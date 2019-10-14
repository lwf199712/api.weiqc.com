<?php

use app\models\dataObject\DesignCenterImageDo;
use yii\db\Migration;
use yii\db\Query;


class m191014_120233_create_six_table_insert_design_center_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $designFields = ['design_finish_time', 'name', 'stylist', 'picture_address', 'upload_time', 'audit_status', 'audit_opinion', 'auditor', 'audit_time', 'size', 'type'];

        $tableData = (new Query())
            ->select('*')
            ->from('bm_design_center')
            ->all();

        foreach ($tableData as $key => $value) {
            unset($tableData[$key]['id']);
            if (!empty($value['version'])) {
                //数据表存的是2018年，2019年，需要特殊处理
                $arr = date_parse_from_format('Y年m月d日H:i:s', $value['version']);
                $tableData[$key]['design_finish_time'] = mktime($arr['hour'], $arr['minute'], $arr['second'], $arr['month'], $arr['day'], $arr['year']) + 2736000;
            }
            //处理完成,清除该字段
            unset($tableData[$key]['version']);
            $tableData[$key]['size'] = '';
            //插入类型type
            $tableData[$key]['type'] = 'homePage';
        }

        try {
            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), $designFields, $tableData)
                ->execute();
        } catch (\yii\db\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
