<?php

use yii\db\Migration;

/**
 * Class m190921_024942_create_table_index_img
 */
class m190921_024942_create_table_index_img extends Migration
{
    public $tableName = '{{%index_img}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                => $this->primaryKey(),
            'version'           => $this->string()->notNull()->comment('版本'),
            'name'              => $this->string()->notNull()->comment('名称'),
            'stylist'           => $this->string()->notNull()->comment('设计师'),
            'picture_address'   => $this->string(1024)->notNull()->comment('图片地址'),
            'upload_time'       => $this->integer(11)->null()->defaultValue(0)->comment('上传时间'),
            'audit_status'      => $this->integer(1)->null()->defaultValue(0)->comment('审核状态(0/待审核，1/通过，2/不通过)'),
            'audit_opinion'     => $this->string()->null()->comment('审核意见'),
            'auditor'           => $this->string()->null()->comment('审核人'),
            'audit_time'       => $this->integer(11)->null()->defaultValue(0)->comment('审核时间'),
            'size'             =>$this->string(20)->notNull()->comment('图片规格'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190921_024942_create_table_index_img cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190921_024942_create_table_index_img cannot be reverted.\n";

        return false;
    }
    */
}
