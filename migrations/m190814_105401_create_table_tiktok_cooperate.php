<?php

use yii\db\Migration;

/**
 * Class m190814_105401_create_table_tiktok_cooperate
 */
class m190814_105401_create_table_tiktok_cooperate extends Migration
{
    public $tableName = '{{%tiktok_cooperate}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                    => $this->primaryKey(),
            'nickname'              => $this->string()->notNull()->comment('昵称'),
            'channel'               => $this->string()->notNull()->comment('渠道'),
            'fans_num'              => $this->integer()->notNull()->comment('粉丝量'),
            'time'                  => $this->integer()->notNull()->comment('时间'),
            'authorize_performance' => $this->string()->null()->comment('授权平台'),
            'authorize_time'        => $this->integer()->null()->comment('授权时间'),
            'authorize_remark'      => $this->string()->null()->comment('授权备注'),
            'kol_info'              => $this->string()->notNull()->comment('KOL具体信息'),
            'follow'                => $this->string()->notNull()->comment('跟进人'),
            'link'                  => $this->string()->notNull()->comment('链接'),
            'draft_quotation'       => $this->string()->notNull()->comment('初步报价'),
            'draft_verify'          => $this->integer(1)->notNull()->defaultValue(0)->comment('初审（0待定/1否/2是）'),
            'draft_verify_remark'   => $this->string()->notNull()->comment('初审备注'),
            'video_num'             => $this->integer()->null()->comment('视频数'),
            'final_price'           => $this->string()->null()->comment('最终价格'),
            'final_verify'          => $this->integer(1)->null()->comment('终审（0待定/1否/2是）'),
            'final_verify_remark'   => $this->string()->null()->comment('终审备注'),
            'product'               => $this->string()->null()->comment('产品'),
            'cooperate_pattern'     => $this->string()->null()->comment('合作模式'),
            'dept'                  => $this->string()->notNull()->comment('部门'),
        ]);
        $this->createIndex('main_index', $this->tableName, ['nickname'], true);
        $this->createIndex('search_index', $this->tableName, ['cooperate_pattern', 'nickname', 'product', 'follow', 'draft_verify', 'final_verify']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190814_105401_create_table_tiktok_cooperate cannot be reverted.\n";

        return false;
    }
    */
}
