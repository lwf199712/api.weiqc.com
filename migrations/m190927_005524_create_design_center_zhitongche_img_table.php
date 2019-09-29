<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%design_center_zhitongche_img}}`.
 */
class m190927_005524_create_design_center_zhitongche_img_table extends Migration
{
    public $tableName = '{{%design_center_zhitongche_img}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                => $this->primaryKey(),
            'version'           => $this->string()->notNull()->comment('完成日期'),
            'name'              => $this->string()->notNull()->comment('名称'),
            'stylist'           => $this->string()->notNull()->comment('设计师'),
            'picture_address'   => $this->string(1024)->notNull()->comment('图片地址'),
            'upload_time'       => $this->integer(11)->null()->defaultValue(0)->comment('上传时间'),
            'audit_status'      => $this->integer(1)->null()->defaultValue(0)->comment('审核状态(0/待审核，1/通过，2/不通过)'),
            'audit_opinion'     => $this->string()->null()->comment('审核意见'),
            'auditor'           => $this->string()->null()->comment('审核人'),
            'audit_time'       => $this->integer(11)->null()->defaultValue(0)->comment('审核时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%design_center_zhitongche_img}}');
    }
}
