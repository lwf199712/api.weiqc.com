<?php

use yii\db\Migration;

/**
 * Class m190810_094954_create_table_tiktok_resource_base_cooperate
 */
class m190810_094954_create_table_tiktok_resource_base_cooperate extends Migration
{
    public $tableName = '{{%tiktok_resource_base_cooperate}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                  => $this->primaryKey(),
            'resource_base_id'    => $this->integer()->notNull()->comment('资源库ID'),
            'channel'             => $this->string()->null()->comment('渠道'),
            'kol_name'            => $this->string()->notNull()->comment('kol名称'),
            'account_id'          => $this->string()->notNull()->comment('账号ID'),
            'talent_introduction' => $this->text()->null()->comment('达人简介'),
            'account_type'        => $this->string()->null()->comment('账号类型'),
            'account_link'        => $this->string()->null()->comment('账号链接'),
            'fans_num'            => $this->integer()->null()->comment('粉丝量(W)'),
            'quotation'           => $this->integer()->null()->comment('合作价格/报价(W)'),
            'cooperate_video_num' => $this->integer()->null()->comment('已合作视频数'),
            'cooperate_fee'       => $this->integer()->null()->comment('已合作费用（W）'),
            'cooperate_info'      => $this->string()->null()->comment('合作情况'),
            'contact'             => $this->string()->null()->comment('联系人'),
            'contact_info'        => $this->string()->null()->comment('联系方式'),
            'updated_at'          => $this->integer()->null()->comment('更新时间'),
            'follow'              => $this->string()->null()->comment('跟进人'),
        ]);
        $this->createIndex('main_index',$this->tableName,['account_id','kol_name'],true);
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
        echo "m190810_094954_create_table_tiktok_resource_base_cooperate cannot be reverted.\n";

        return false;
    }
    */
}
