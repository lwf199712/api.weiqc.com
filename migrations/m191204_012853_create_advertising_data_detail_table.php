<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advertising_data_detail}}`.
 */
class m191204_012853_create_advertising_data_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public $tableName = '{{%advertising_data_detail}}';
    public function safeUp()
    {
        $tableOptions = '';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB COMMENT="市场部-投放数据表市场部-投放数据详情"';
        }
        $this->createTable($this->tableName, [
            'id'                  => $this->primaryKey(),
            'ads_id'              => $this->integer(11)->notNull()->defaultValue(0)->comment('市场部-投放数据表的id'),
            'consume'             => $this->Integer(10)->notNull()->defaultValue(0)->comment('消耗'),
            'r_id'                => $this->Integer(11)->notNull()->defaultValue(0)->comment('渠道id，对应渠道返点表的id'),
            'p_id'                => $this->Integer(11)->defaultValue(0)->comment('落地页id，对应advertising_data_page的id'),
            'v_id'                => $this->Integer(11)->notNull()->defaultValue(0)->comment('视频id，对应视频表的id'),
            'a_id'                => $this->integer(11)->notNull()->comment('代理商id，对应账号表的id(advertising_data_account)'),
            'd_id'                => $this->Integer(12)->comment('大投数据统计/测试数据统计id'),
            'click_rate'          => $this->string(10)->defaultValue('')->comment('点击率'),
            'conversion_rate'     => $this->string(10)->defaultValue('')->comment('转化率'),
            'dc_conversion_rate'  => $this->string(10)->defaultValue('')->comment('DC转化率'),
            'leakage_rate'        => $this->string(10)->defaultValue('')->comment('漏粉率'),
            'number'              => $this->string(64)->defaultValue('')->comment('编号名称'),
            'is_test'             => $this->integer(11)->comment('是否是测试编号(0/不是 1/是)'),
            'is_delete'           => $this->tinyInteger(4)->notNull()->defaultValue(0)->comment('软删除（0：未删除，1：删除）'),
        ], $tableOptions);
        $this->createIndex('search_index', $this->tableName, ['ads_id']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advertising_data_detail}}');
    }
}
