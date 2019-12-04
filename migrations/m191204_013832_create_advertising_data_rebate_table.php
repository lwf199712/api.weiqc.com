<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advertising_data_rebate}}`.
 */
class m191204_013832_create_advertising_data_rebate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public $tableName = '{{%advertising_data_rebate}}';
    public function safeUp()
    {
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'sCHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="市场部-投放数据-渠道返点表"';
        }
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'channel_name' => $this->string(32)->notNull()->defaultValue('')->comment('渠道返点名'),
            'rebate'       => $this->decimal(6,3)->notNull()->defaultValue(0.000)->comment('具体返点，如1.350'),
            'c_id'         => $this->Integer(11)->notNull()->defaultValue(0)->comment('渠道ID'),
            'is_delete'    => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('是否删除，0/未删除，1/已删除'),
            'creator'      => $this->string(32)->notNull()->defaultValue('')->comment('创建人'),
            'create_time'  => $this->integer(16)->notNull()->defaultValue(0)->comment('创建时间'),
            'update_user'  => $this->string(32)->defaultValue('')->comment('修改人'),
            'update_time'  => $this->integer(16)->defaultValue(0)->comment('修改时间'),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advertising_data_rebate}}');
    }
}
