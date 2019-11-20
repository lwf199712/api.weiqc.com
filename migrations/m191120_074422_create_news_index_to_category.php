<?php

use yii\db\Migration;

/**
 * Class m191120_074422_create_news_index_to_category
 */
class m191120_074422_create_news_index_to_category extends Migration
{
    public $tableName = '{{%CategoryManagement}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('uniq_category_category', $this->tableName,['category'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191120_074422_create_news_index_to_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191120_074422_create_news_index_to_category cannot be reverted.\n";

        return false;
    }
    */
}
