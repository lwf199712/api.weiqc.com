<?php

use yii\db\Migration;

/**
 * Class m191119_052721_update_physical_replace_order_tablble_fans_amount
 */
class m191119_052721_update_physical_replace_order_tablble_fans_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%physical_replace_order}}','fans_amount',$this->string(255)->defaultValue('')->comment('粉丝量'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191119_052721_update_physical_replace_order_tablble_fans_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191119_052721_update_physical_replace_order_tablble_fans_amount cannot be reverted.\n";

        return false;
    }
    */
}
