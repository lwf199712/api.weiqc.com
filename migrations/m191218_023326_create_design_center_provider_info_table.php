<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%design_center_provider_info}}`.
 */
class m191218_023326_create_design_center_provider_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="设计中心-供应商信息表"';
        }
        $this->createTable('{{%design_center_provider_info}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull()->defaultValue('')->comment('视频供应商/外包设计公司'),
            'quoted_price' => $this->string(32)->notNull()->defaultValue('')->comment('报价'),
            'site' => $this->string(32)->notNull()->defaultValue('')->comment('地点'),
            'recommended_reason' => $this->string(128)->notNull()->defaultValue('')->comment('推荐理由'),
            'contact_way' => $this->string(16)->notNull()->defaultValue('')->comment('联系方式'),
            'remark' => $this->string()->notNull()->defaultValue('')->comment('备注'),
            'reference_case' => $this->string()->notNull()->defaultValue('')->comment('参考案例'),
            'flag' => $this->string(16)->notNull()->defaultValue('')->comment('标识（video/outer）'),
            'created_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00')->comment('创建时间'),
            'updated_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00')->comment('更新时间'),
            'creator' => $this->string(16)->notNull()->defaultValue('')->comment('创建人'),
            'updater' => $this->string(16)->notNull()->defaultValue('')->comment('更新人'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%design_center_provider_info}}');
    }
}
