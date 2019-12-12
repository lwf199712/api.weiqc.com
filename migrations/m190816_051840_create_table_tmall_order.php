<?php

use yii\db\Migration;

/**
 * Class m190816_051840_create_table_tmall_order
 */
class m190816_051840_create_table_tmall_order extends Migration
{
    public $tableName = '{{%tmall_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName,[
            'create_at' => $this->integer(11)->notNull()->comment('创建时间'),
            'phone' => $this->string(20)->notNull()->comment('手机'),
            'price' => $this->integer()->notNull()->comment('金额(分)'),
        ]);
        $this->createIndex('time_index',$this->tableName,['create_at']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190816_051840_create_table_tmall_order cannot be reverted.\n";

        return false;
    }
    */
}
