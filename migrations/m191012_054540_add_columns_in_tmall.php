<?php

use yii\db\Migration;

/**
 * Class m191012_054540_add_columns_in_tmall
 */
class m191012_054540_add_columns_in_tmall extends Migration
{
    public $tableName = '{{%tmall_order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName,'id',$this->primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName,'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191012_054540_add_columns_in_tmall cannot be reverted.\n";

        return false;
    }
    */
}
