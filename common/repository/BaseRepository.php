<?php
declare(strict_types=1);

namespace app\common\repository;

use app\modules\v2\marketDept\domain\dto\TikTokResourceBaseCooperateDto;
use RuntimeException;
use yii\base\BaseObject;
use yii\base\ExitException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Class BaseRepository
 * @property ActiveRecord $model
 * @property ActiveQuery $query
 * @package app\common\repository11
 */
abstract class BaseRepository extends BaseObject
{
    /** @var string $modelClass */
    public static $modelClass;

    /**
     * @Inject(static::model)
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var ActiveQuery
     */
    public $query;


    /**
     * BaseRepository constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->model = new static::$modelClass;
        $this->query = $this->model::find();
        parent::__construct($config);
    }

}