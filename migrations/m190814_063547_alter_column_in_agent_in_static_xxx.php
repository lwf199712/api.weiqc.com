<?php

use yii\db\Migration;

/**
 * Class m190814_063547_alter_column_in_agent_in_static_xxx
 */
class m190814_063547_alter_column_in_agent_in_static_xxx extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%statis_client}}','agent',$this->string(325)->comment('代理商'));
        $this->alterColumn('{{%statis_visit}}','agent',$this->string(325)->comment('代理商'));
        $this->alterColumn('{{%statis_conversion}}','agent',$this->string(325)->comment('代理商'));
        $this->alterColumn('{{%statis_hits}}','agent',$this->string(325)->comment('代理商'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%statis_client}}','agent',$this->string(225)->comment('代理商'));
        $this->alterColumn('{{%statis_visit}}','agent',$this->string(225)->comment('代理商'));
        $this->alterColumn('{{%statis_conversion}}','agent',$this->string(225)->comment('代理商'));
        $this->alterColumn('{{%statis_hits}}','agent',$this->string(225)->comment('代理商'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190814_063547_alter_column_in_agent_in_static_xxx cannot be reverted.\n";

        return false;
    }
    */
}
