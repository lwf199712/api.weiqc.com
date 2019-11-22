<?php

use yii\db\Migration;

/**
 * Class m191122_005752_update_physical_send_status_table_phone
 */
class m191122_005752_update_physical_send_status_table_phone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%physical_send_status}}','phone',$this->string()->defaultValue('')->comment('联系电话'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191122_005752_update_physical_send_status_table_phone cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191122_005752_update_physical_send_status_table_phone cannot be reverted.\n";

        return false;
    }
    */
}
