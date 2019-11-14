<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%physical_send_status}}`.
 */
class m191109_052851_create_physical_send_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%physical_send_status}}', [
            'id' => $this->primaryKey(),
            'recipients'        => $this->string()->null()->defaultValue('')->comment('收件人'),
            'phone'             => $this->integer(11)->null()->defaultValue('')->comment('联系电话'),
            'delivery_site'     => $this->string()->null()->defaultValue('')->comment('收货地址'),
            'tracking_number'   => $this->string()->notNull()->defaultValue('')->comment('快递单号'),
            'rp_id'             => $this->integer(11)->null()->defaultValue(0)->comment('置换订单'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%physical_send_status}}');
    }
}
