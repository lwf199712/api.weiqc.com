<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%physical_replace_order}}`.
 */
class m191109_032136_create_physical_replace_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%physical_replace_order}}', [
            'id'                    => $this->primaryKey(),
            'nick_name'             => $this->string()->notNull()->defaultValue('')->comment('昵称'),
            'we_chat_id'            => $this->string()->notNull()->defaultValue('')->comment('微信号'),
            'fans_amount'           =>  $this->integer(11)->null()->defaultValue(0)->comment('粉丝量'),
            'advert_location'       => $this->string()->null()->defaultValue('')->comment('广告位置'),
            'put_times'             => $this->integer(11)->null()->defaultValue(0)->comment('投放次数'),
            'dispatch_time'         => $this->integer(11)->notNull()->defaultValue(0)->comment('发文时间'),
            'follower'              => $this->string()->null()->comment('跟进人'),
            'female_powder_proportion' => $this->decimal(11,2)->null()->defaultValue(0)->comment('女粉占比'),
            'put_link'              => $this->string(1024)->null()->defaultValue('')->comment('投放链接'),
            'replace_product'       => $this->string()->null()->defaultValue('')->comment('置换产品'),
            'replace_quantity'      => $this->integer(11)->null()->defaultValue(0)->comment('置换件数'),
            'brand'                 => $this->string()->null()->defaultValue('')->comment('品牌'),
            'average_reading'       => $this->integer(11)->null()->defaultValue(0)->comment('平均阅读量'),
            'account_type'          => $this->string()->null()->defaultValue('')->comment('账号类型'),
            'first_trial'           => $this->integer(1)->null()->defaultValue(0)->comment('初审（0/待审核1/已通过2/不通过）'),
            'final_judgment'        => $this->integer(1)->null()->defaultValue(0)->comment('终审（0/待审核1/已通过2/不通过）'),
            'prize_send_status'     => $this->integer(1)->null()->defaultValue(0)->comment('奖品寄出状态（0/未发货1/已发货）'),
            'advert_read_num'       => $this->integer(11)->null()->defaultValue(0)->comment('广告阅读数'),
            'volume_transaction'    => $this->decimal(11,2)->null()->defaultValue(0)->comment('成交额'),
            'new_fan_attention'     => $this->integer(11)->null()->defaultValue(0)->comment('新粉丝关注数'),
            'first_audit_opinion'   => $this->string()->null()->defaultValue('')->comment('初审审核意见'),
            'final_audit_opinion'   => $this->string()->null()->defaultValue('')->comment('终审审核意见'),
            'first_auditor'         => $this->string()->null()->defaultValue('')->comment('初审人'),
            'final_auditor'         => $this->string()->null()->defaultValue('')->comment('终审人'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%physical_replace_order}}');
    }
}
