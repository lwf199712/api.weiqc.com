<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistics_service}}`.
 */
class m191205_121505_create_statistics_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public $tableName = '{{%statistics_service}}';
    public function safeUp()
    {
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="链接-服务号"';
        }

        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'name'        => $this->string(64)->notNull()->defaultValue('')->comment('服务号名称'),
            'account'     => $this->string(64)->notNull()->defaultValue('')->comment('服务账号'),
            'created_at' => $this->integer(32)->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_time' => $this->integer(32)->unsigned()->notNull()->defaultValue(0)->comment('更新时间'),
            'creator'     => $this->string(16)->notNull()->defaultValue('')->comment('创建人'),
            'updated_at'  => $this->integer(32)->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),
            'deleted_at'  => $this->integer(32)->unsigned()->notNull()->defaultValue(0)->comment('删除时间'),
            'updater'     => $this->string(16)->notNull()->defaultValue('')->comment('创建人'),
            'deleter'     => $this->string(16)->notNull()->defaultValue('')->comment('删除人'),

        ], $tableOptions);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%statistics_service}}');
    }
}
