<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_library}}`.
 */
class m191008_093013_create_product_library_table extends Migration
{
    public $tableName = '{{%product_library}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'product_name' => $this->string()->notNull()->comment('产品名称'),
            'commission_rate' => $this->decimal(7,2)->notNull()->comment('抽佣率（%）'),
            'founder' => $this->string()->notNull()->comment('创建人'),
            'create_at' => $this->integer()->defaultValue(0)->comment('创建时间'),
        ]);
        $this->createIndex('uniq_product_name', $this->tableName, 'product_name', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
