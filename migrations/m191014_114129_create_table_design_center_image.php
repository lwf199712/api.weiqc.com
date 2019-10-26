<?php

use yii\db\Migration;

/**
 * Class m191014_114129_create_table_design_center_image
 */
class m191014_114129_create_table_design_center_image extends Migration
{
    public $tableName = '{{%design_center_image}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                => $this->primaryKey(),
            'design_finish_time'=> $this->integer(11)->notNull()->defaultValue(0)->comment('设计完成时间'),
            'name'              => $this->string()->notNull()->comment('名称'),
            'stylist'           => $this->string()->notNull()->comment('设计师'),
            'picture_address'   => $this->string(1024)->notNull()->comment('图片地址'),
            'upload_time'       => $this->integer(11)->notNull()->defaultValue(0)->comment('上传时间'),
            'audit_status'      => $this->integer(1)->null()->defaultValue(0)->comment('审核状态(0/待审核，1/通过，2/不通过)'),
            'audit_opinion'     => $this->string()->null()->comment('审核意见'),
            'auditor'           => $this->string()->null()->comment('审核人'),
            'audit_time'        => $this->integer(11)->null()->defaultValue(0)->comment('审核时间'),
            'size'              => $this->string()->null()->comment('图片规格'),
            'type'              => $this->string()->notNull()->comment('类型（homePage/首页图片，mainImage/主图，productDetail/产品详情，drillShow/钻展，throughCar/直通车，landingPage/落地页）'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
