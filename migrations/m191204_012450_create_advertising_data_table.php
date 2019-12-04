<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advertising_data}}`.
 */
class m191204_012450_create_advertising_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public $tableName = '{{%advertising_data}}';
    public function safeUp()
    {
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="市场部-投放数据表"';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'write_time'   => $this->integer(16)->notNull()->defaultValue(0)->comment('录入时间'),
            'u_id'         => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('投放人员id'),
            's_id'         => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('关联服务号表的id'),
            'is_delete'    => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('是否删除，0/未删除，1/已删除'),
            'creator'      => $this->string(16)->notNull()->defaultValue('')->comment('创建人'),
            'create_time'  => $this->integer(16)->notNull()->defaultValue(0)->comment('创建时间'),
            'update_user'  => $this->string(16)->defaultValue('')->comment('修改人'),

        ], $tableOptions);
        $this->createIndex('search_index', $this->tableName, ['write_time', 'is_delete']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advertising_data}}');
    }
}
