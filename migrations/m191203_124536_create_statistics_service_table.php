<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistics_service}}`.
 */
class m191203_124536_create_statistics_service_table extends Migration
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

        $this->createTable($this->tableName,[
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull()->defaultValue('')->comment('服务号名称'),
            'account' => $this->string(64)->notNull()->defaultValue('')->comment('服务账号'),
            'create_time' => $this->integer(32)->notNull()->defaultValue(0)->comment('创建时间'),
            'is_delete' => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('软删除（0：未删除，1：删除）'),

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
