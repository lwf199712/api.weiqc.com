<?php

use yii\db\Migration;

/**
 * Class m191030_051639_create_table_home_video
 */
class m191030_051639_create_table_home_video extends Migration
{
    public $tableName = '{{%design_center_home_video}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                => $this->primaryKey(),
            'design_finish_time'=> $this->integer(11)->notNull()->defaultValue(0)->comment('设计完成时间'),
            'name'              => $this->string()->notNull()->comment('名称'),
            'video'             => $this->string(1024)->notNull()->comment('图片地址'),
            'upload_time'       => $this->integer(11)->notNull()->defaultValue(0)->comment('上传时间'),
            'audit_status'      => $this->integer(1)->null()->defaultValue(0)->comment('审核状态(0/待审核，1/通过，2/不通过)'),
            'audit_opinion'     => $this->string()->null()->comment('审核意见'),
            'auditor'           => $this->string()->null()->comment('审核人'),
            'audit_time'        => $this->integer(11)->null()->defaultValue(0)->comment('审核时间'),
            'category'          => $this->string(150)->null()->comment('属性类别'),
            'url'               => $this->string(1024)->null()->comment('图片链接')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191030_051639_create_table_home_video cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191030_051639_create_table_home_video cannot be reverted.\n";

        return false;
    }
    */
}
