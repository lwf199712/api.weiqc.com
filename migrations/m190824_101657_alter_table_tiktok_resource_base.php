<?php

use yii\db\Migration;

/**
 * Class m190824_101657_alter_table_tiktok_resource_base
 */
class m190824_101657_alter_table_tiktok_resource_base extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tiktok_resource_base}}','create_at',$this->integer()->notNull()->defaultValue(0)->comment('创建时间'));
        $this->dropIndex('main_index','{{%tiktok_resource_base_cooperate}}');
        $this->createIndex('main_index','{{%tiktok_resource_base_cooperate}}',['account_id','kol_name','resource_base_id'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tiktok_resource_base}}','create_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190824_101657_alter_table_tiktok_resource_base cannot be reverted.\n";

        return false;
    }
    */
}
