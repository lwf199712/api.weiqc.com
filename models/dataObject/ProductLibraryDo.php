<?php declare(strict_types=1);

namespace app\models\dataObject;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bm_product_library".
 *
 * @property int $id
 * @property string $product_name 产品名称
 * @property string $commission_rate 抽佣率（%）
 * @property string $founder 创建人
 * @property int $create_at 创建时间
 */
class ProductLibraryDo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bm_product_library';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_name', 'commission_rate', 'founder', 'create_at'], 'required'],
            [['commission_rate'], 'number'],
            [['create_at'], 'integer'],
            [['product_name', 'founder'], 'string', 'max' => 255],
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
            'commission_rate' => 'Commission Rate',
            'founder' => 'Founder',
            'create_at' => 'Create At',
        ];
    }
}
