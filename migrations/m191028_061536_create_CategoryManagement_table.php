<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%CategoryManagement}}`.
 */
class m191028_061536_create_CategoryManagement_table extends Migration
{
    private $tableName = '{{%CategoryManagement}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="类别表属性"';
        }
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'category' => $this->string(120)->notNull()->defaultValue('')->comment('类别内容'),
            'type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('1 是图片的类别属性，2是视频的类别属性'),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
