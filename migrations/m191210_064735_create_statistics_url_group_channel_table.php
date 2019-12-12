<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistics_url_group_channel}}`.
 */
class m191210_064735_create_statistics_url_group_channel_table extends Migration
{
    /**
     * 统计链接-分组渠道表
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="统计链接-分组渠道表"';
        }
        $this->createTable('{{%statistics_url_group_channel}}', [
            'id' => $this->primaryKey()->comment('主键id'),
            'channel_name' => $this->string(32)->notNull()->defaultValue('')->comment('渠道名称'),
            'creator' => $this->string(16)->notNull()->defaultValue('')->comment('创建者'),
            'created_at' => $this->dateTime()->defaultValue(0)->comment('创建时间'),
            'updater' => $this->string(16)->notNull()->defaultValue('')->comment('更新者'),
            'updated_at' => $this->dateTime()->defaultValue(0)->comment('更新时间'),
            'deleted_at' => $this->dateTime()->defaultValue(0)->comment('删除时间')
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%statistics_url_group_channel}}');
    }
}
