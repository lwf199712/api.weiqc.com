<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%CategoryManagement}}`.
 */
class m191028_061536_create_CategoryManagement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%CategoryManagement}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(120)->notNull()->comment('类别内容'),
            'type' => $this->tinyInteger()->notNull()->comment('1 是图片的类别属性，2是视频的类别属性'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%CategoryManagement}}');
    }
}
