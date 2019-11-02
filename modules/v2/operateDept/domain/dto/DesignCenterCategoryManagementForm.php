<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;

use Exception;
use RuntimeException;
use Yii;
use yii\base\Model;

class DesignCenterCategoryManagementForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $category;
    /** @var int */
    public $type;
    /** @var video */
    public $video;

    public function rules(): array
    {
        return [
            [['id', 'type'], 'integer'],
            [['category'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'                => 'ID',
            'category'          => '属性',
            'type'              => '属性归属',
        ];
    }
}