<?php

use yii\db\Migration;

/**
 * Class m190829_125029_TiktokResourcBaseNew
 */
class m190829_125029_TiktokResourcBaseNew extends Migration
{
    public $tableName = '{{%tiktok_resource_base}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                => $this->primaryKey(),
            'mcn_company_name'  => $this->string(255)->notNull()->comment('机构/公司名称'),
            'header_account'    => $this->text()->null()->comment('头部账号'),
            'cooperate_info'    => $this->text()->null()->comment('合作情况'),
            'company_business'  => $this->text()->null()->comment('公司主要业务'),
            'company_address'   => $this->text()->null()->comment('公司地址'),
            'identity'          => $this->integer(1)->null()->comment('MCN机构、中介、个人'),
            'account_num'       => $this->string()->null()->comment('账号总数量'),
            'single_account'    => $this->string()->null()->comment('独家账号'),
            'depend_account'    => $this->string()->null()->comment('挂靠账号'),
            'cooperate_channel' => $this->string()->null()->comment('可合作渠道'),
            'main_account_type' => $this->string()->null()->comment('主要账号类型'),
            'fans_num'          => $this->integer()->null()->comment('总粉丝数量(W)'),
            'cooperate_num'     => $this->integer()->null()->comment('已合作达人数'),
            'cooperate_fee'     => $this->integer()->null()->comment('已合作总费用(W)'),
            'per_month_fee'     => $this->integer()->null()->comment('每月接单费用(W)'),
            'publication'       => $this->string()->null()->comment('刊例'),
            'update_at'         => $this->integer()->null()->comment('更新时间'),
            'follow'            => $this->string()->notNull()->comment('跟进人'),
        ]);

        $this->createIndex('main_index', $this->tableName, ['mcn_company_name', 'follow'], true);
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
        echo "m190829_125029_TiktokResourcBaseNew cannot be reverted.\n";

        return false;
    }
    */
}
