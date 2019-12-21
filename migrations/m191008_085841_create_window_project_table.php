<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%window_project}}`.
 */
class m191008_085841_create_window_project_table extends Migration
{
    public $tableName = '{{%window_project}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'product_name' => $this->string(255)->notNull()->comment('产品名称'),
            'data_time' => $this->integer()->notNull()->comment('日期'),
            'period' => $this->tinyInteger(4)->notNull()->comment('时间段(0~23,[0/0-1,1/1-2...以此类推])'),
            'account_and_id' => $this->string()->notNull()->comment('账号+淘宝ID'),
            'delivery_platform' => $this->string()->notNull()->comment('投放平台（MVEBackstage/MVE后台、WISBackstage/WIS后台、WISXiaoXi/WIS小希）'),
            'video_name' => $this->string()->notNull()->comment('视频名称'),
            'consume' => $this->decimal(11,2)->notNull()->comment('消耗'),
            'total_turnover' => $this->decimal(12,2)->notNull()->comment('总成交'),
            'real_turnover' => $this->decimal(12,2)->notNull()->comment('实时成交'),
            'transaction_data' => $this->decimal(12,2)->notNull()->comment('生意参谋成交数据'),
            'responsible_person' => $this->string()->notNull()->comment('负责人'),
            'create_at' => $this->integer()->defaultValue(0)->comment('创建时间'),
        ]);
        $this->addCommentOnTable($this->tableName, '橱窗项目表');
        $this->createIndex('idx_product_name', $this->tableName, 'product_name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
