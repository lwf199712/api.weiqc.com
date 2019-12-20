<?php

use yii\db\Migration;

/**
 * Class m191220_062529_update_designgn_center_home_video_thumbnail
 */
class m191220_062529_update_designgn_center_home_video_thumbnail extends Migration
{
    private $tableName = '{{%design_center_home_video}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%design_center_home_video}}','thumbnail',$this->string(1024)->null()->defaultValue('')->comment('缩略图链接'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName,'thumbnail');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191220_062529_update_designgn_center_home_video_thumbnail cannot be reverted.\n";

        return false;
    }
    */
}
