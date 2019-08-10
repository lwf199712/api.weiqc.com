<?php

use yii\db\Migration;

/**
 * Class m190806_051517_add_column_to_user_table
 */
class m190806_051517_add_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}','access_token',$this->string()->notNull()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}','access_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190806_051517_add_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
