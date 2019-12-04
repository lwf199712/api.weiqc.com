<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advertising_data_page}}`.
 */
class m191204_013521_create_advertising_data_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public $tableName = '{{%advertising_data_page}}';
    public function safeUp()
    {
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="市场部-投放数据-落地页表"';
        }
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'page_name'   => $this->integer(32)->notNull()->defaultValue(''),
            'link'        => $this->string(225)->defaultValue('')->comment('消耗'),
            'is_delete'   => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('是否删除，0/未删除，1/已删除v'),
            'creator'     => $this->string(32)->notNull()->defaultValue(''),
            'create_time' => $this->Integer(16)->notNull()->defaultValue(0),
            'update_user' => $this->string(32)->defaultValue(''),
            'update_time' => $this->Integer(16),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advertising_data_page}}');
    }
}
