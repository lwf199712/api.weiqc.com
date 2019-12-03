<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistics_url_group_channel}}`.
 */
class m191202_120655_create_statistics_url_group_channel_table extends Migration
{
    /**
     * 统计链接-分组渠道表
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%statistics_url_group_channel}}', [
            'id' => $this->primaryKey(),
            'channel_name' => $this->string(32)->notNull()->defaultValue('')->comment('渠道名称'),
            'creator' => $this->string(16)->notNull()->defaultValue('')->comment('创建者'),
            'create_time' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建者'),
            'updater' => $this->string(16)->notNull()->defaultValue('')->comment('更新者'),
            'update_time' => $this->integer(11)->notNull()->defaultValue(0)->comment('更新时间'),
            'is_delete' => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('删除：0-未删除/1-已删除')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%statistics_url_group_channel}}');
    }
}
