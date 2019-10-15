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
        $designFields = ['name', 'stylist', 'picture_address', 'upload_time', 'audit_status', 'audit_opinion', 'auditor', 'audit_time', 'design_finish_time', 'size', 'type'];
        //首页图片
        $tableData = (new Query())
            ->select('*')
            ->from('bm_design_center')
            ->all();
        //主图
        $tableDataIndexImg = (new Query())
            ->select('*')
            ->from('bm_design_center_index_img')
            ->all();
        //落地页
        $tableDataLandingPage = (new Query())
            ->select('*')
            ->from('bm_design_center_landingpage_img')
            ->all();
        //产品详情
        $tableDataProductDetail = (new Query())
            ->select('*')
            ->from('bm_design_center_product_detail_img')
            ->all();
        //直通车
        $tableDataThroughCar = (new Query())
            ->select('*')
            ->from('bm_design_center_zhitongche_img')
            ->all();
        //钻展图
        $tableDataDrillShow = (new Query())
            ->select('*')
            ->from('bm_design_center_zuanzhan_img')
            ->all();
        //批量插入首页图片
        try {
            foreach ($tableData as $key => $value) {
                if (!empty($value['version'])) {
                    //数据表存的是2018年，2019年，需要特殊处理
                    $arr = date_parse_from_format('Y年m月d日H:i:s', $value['version']);
                    $tableData[$key]['design_finish_time'] = mktime($arr['hour'], $arr['minute'], $arr['second'], $arr['month'], $arr['day'], $arr['year']) + 2736000;
                }
                $tableData[$key]['size'] = '';
                //插入类型type
                $tableData[$key]['type'] = 'homePage';
                //处理完成,清除该字段
                unset($tableData[$key]['id'], $tableData[$key]['version']);
            }
            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), $designFields, $tableData)
                ->execute();
        } catch (\yii\db\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
        //批量插入主图
        try {
            foreach ($tableDataIndexImg as $key1 => $value1) {
                if (!empty($value1['version'])) {
                    //数据表存的是2018年，2019年，需要特殊处理
                    $tableDataIndexImg[$key1]['design_finish_time'] = strtotime($value1['version']) - 28800;
                } else {
                    $tableDataIndexImg[$key1]['design_finish_time'] = 0;
                }
                //插入类型type
                $tableDataIndexImg[$key1]['type'] = 'mainImage';
                //处理完成,清除该字段
                unset($tableDataIndexImg[$key1]['id'], $tableDataIndexImg[$key1]['version']);
            }

            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), ['name', 'stylist', 'picture_address', 'upload_time', 'audit_status', 'audit_opinion', 'auditor', 'audit_time', 'size', 'design_finish_time', 'type'], $tableDataIndexImg)
                ->execute();
        } catch (\yii\db\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
        //批量插入落地页
        try {
            foreach ($tableDataLandingPage as $key2 => $value2) {
                if (!empty($value2['version'])) {
                    $tableDataLandingPage[$key2]['design_finish_time'] = strtotime($value2['version']) - 28800;
                } else {
                    $tableDataLandingPage[$key2]['design_finish_time'] = 0;
                }
                $tableDataLandingPage[$key2]['size'] = '';
                //插入类型type
                $tableDataLandingPage[$key2]['type'] = 'landingPage';
                //处理完成,清除该字段
                unset($tableDataLandingPage[$key2]['id'], $tableDataLandingPage[$key2]['version']);
            }
            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), $designFields, $tableDataLandingPage)
                ->execute();
        } catch (\yii\db\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        //批量插入产品详情
        try {
            foreach ($tableDataProductDetail as $key3 => $value3) {
                if (!empty($value3['version'])) {
                    $tableDataProductDetail[$key3]['design_finish_time'] = strtotime($value3['version']) - 28800;
                } else {
                    $tableDataProductDetail[$key3]['design_finish_time'] = 0;
                }
                $tableDataProductDetail[$key3]['size'] = '';
                //插入类型type
                $tableDataProductDetail[$key3]['type'] = 'productDetail';
                //处理完成,清除该字段
                unset($tableDataProductDetail[$key3]['id'], $tableDataProductDetail[$key3]['version']);
            }
            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), $designFields, $tableDataProductDetail)
                ->execute();
        } catch (\yii\db\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        //批量插入直通车
        try {
            foreach ($tableDataThroughCar as $key4 => $value4) {
                if (!empty($value4['version'])) {
                    $tableDataThroughCar[$key4]['design_finish_time'] = strtotime($value4['version']) - 28800;
                } else {
                    $tableDataThroughCar[$key4]['design_finish_time'] = 0;
                }
                $tableDataThroughCar[$key4]['size'] = '';
                //插入类型type
                $tableDataThroughCar[$key4]['type'] = 'throughCar';
                //处理完成,清除该字段
                unset($tableDataThroughCar[$key4]['id'], $tableDataThroughCar[$key4]['version']);
            }
            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), $designFields, $tableDataThroughCar)
                ->execute();
        } catch (\yii\db\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        //批量插入钻展
        try {
            foreach ($tableDataDrillShow as $key5 => $value5) {
                if (!empty($value5['version'])) {
                    $tableDataDrillShow[$key5]['design_finish_time'] = strtotime($value5['version']) - 28800;
                } else {
                    $tableDataDrillShow[$key5]['design_finish_time'] = 0;
                }
                $tableDataDrillShow[$key5]['size'] = '';
                //插入类型type
                $tableDataDrillShow[$key5]['type'] = 'drillShow';
                //处理完成,清除该字段
                unset($tableDataDrillShow[$key5]['id'], $tableDataDrillShow[$key5]['version']);
            }
            Yii::$app->db->createCommand()
                ->batchInsert(DesignCenterImageDo::tableName(), $designFields, $tableDataDrillShow)
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
