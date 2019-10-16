<?php
declare(strict_types=1);

namespace app\models\dataObject;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_mktad_detail".
 *
 * @property int    $id
 * @property int    $mkt_id             市场部-投放数据表的id
 * @property int    $consume            消耗
 * @property int    $r_id               渠道id，对应渠道返点表的id
 * @property int    $p_id               落地页id，对应mktad_page的id
 * @property int    $v_id               视频id，对应视频表的id
 * @property int    $a_id               代理商id，对应账号表的id(mktad_account)
 * @property int    $d_id               大投数据统计/测试数据统计id
 * @property string $click_rate         点击率
 * @property string $conversion_rate    转化率
 * @property string $dc_conversion_rate DC转化率
 * @property string $leakage_rate       漏粉率
 * @property string $number             编号名称
 * @property int    $is_test            是否是测试编号(0/不是 1/是)
 */
class MktadDetailDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%mktad_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['mkt_id', 'consume', 'r_id', 'p_id', 'v_id', 'a_id', 'd_id', 'is_test'], 'integer'],
            [['a_id', 'number', 'is_test'], 'required'],
            [['click_rate', 'conversion_rate', 'dc_conversion_rate', 'leakage_rate'], 'string', 'max' => 10],
            [['number'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                 => 'ID',
            'mkt_id'             => 'Mkt ID',
            'consume'            => 'Consume',
            'r_id'               => 'R ID',
            'p_id'               => 'P ID',
            'v_id'               => 'V ID',
            'a_id'               => 'A ID',
            'd_id'               => 'D ID',
            'click_rate'         => 'Click Rate',
            'conversion_rate'    => 'Conversion Rate',
            'dc_conversion_rate' => 'Dc Conversion Rate',
            'leakage_rate'       => 'Leakage Rate',
            'number'             => 'Number',
            'is_test'            => 'Is Test',
        ];
    }

}
