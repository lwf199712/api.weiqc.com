<?php

use yii\db\Migration;

/**
 * Class m191029_051737_add_columns_in_design_center_image
 */
class m191029_051737_add_columns_in_design_center_image extends Migration
{
    private $tableName = '{{%design_center_image}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%design_center_image}}','url',$this->string(1024)->null()->defaultValue('')->comment('下载链接'));
        $this->addColumn('{{%design_center_image}}','category',$this->string(1024)->null()->defaultValue('')->comment('属性'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName,'url');
        $this->dropColumn($this->tableName,'category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191029_051737_add_columns_in_design_center_image cannot be reverted.\n";

        return false;
    }
    */
}
