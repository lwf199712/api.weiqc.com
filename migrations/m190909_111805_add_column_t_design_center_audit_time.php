<?php

use yii\db\Migration;

/**
 * Class m190909_111805_add_column_t_design_center_audit_time
 */
class m190909_111805_add_column_t_design_center_audit_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%design_center}}','audit_time',$this->integer(11)->defaultValue(0)->comment('审核时间'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%design_center}}','audit_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190909_111805_add_column_t_design_center_audit_time cannot be reverted.\n";

        return false;
    }
    */
}
