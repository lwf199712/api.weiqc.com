<?php declare(strict_types=1);

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_window_project".
 *
 * @property int $id
 * @property string $product_name 产品名称
 * @property int $data_time 日期
 * @property int $period 时间段(0~23,[0/0-1,1/1-2...以此类推])
 * @property string $account_and_id 账号+淘宝ID
 * @property string $delivery_platform 投放平台
 * @property string $video_name 视频名称
 * @property string $consume 消耗
 * @property string $total_turnover 总成交
 * @property string $real_turnover 实时成交
 * @property string $transaction_data 生意参谋成交数据
 * @property string $responsible_person 负责人
 * @property int $create_at 创建时间
 */
class WindowProjectDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_window_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_name', 'data_time', 'period', 'account_and_id', 'delivery_platform', 'video_name', 'consume', 'total_turnover', 'real_turnover', 'transaction_data', 'responsible_person', 'create_at'], 'required'],
            [['data_time', 'period', 'create_at'], 'integer'],
            [['consume', 'total_turnover', 'real_turnover', 'transaction_data'], 'number'],
            [['product_name', 'account_and_id', 'delivery_platform', 'video_name', 'responsible_person'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_name' => 'Product Name',
            'data_time' => 'Data Time',
            'period' => 'Period',
            'account_and_id' => 'Account And ID',
            'delivery_platform' => 'Delivery Platform',
            'video_name' => 'Video Name',
            'consume' => 'Consume',
            'total_turnover' => 'Total Turnover',
            'real_turnover' => 'Real Turnover',
            'transaction_data' => 'Transaction Data',
            'responsible_person' => 'Responsible Person',
            'create_at' => 'Create At',
        ];
    }
}
