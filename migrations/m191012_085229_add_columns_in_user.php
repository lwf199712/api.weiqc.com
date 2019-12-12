<?php

use yii\db\Migration;

/**
 * Class m191012_085229_add_columns_in_user
 */
class m191012_085229_add_columns_in_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}','realname',$this->string()->notNull()->defaultValue(''));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}','realname');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191012_085229_add_columns_in_user cannot be reverted.\n";

        return false;
    }
    */
}
