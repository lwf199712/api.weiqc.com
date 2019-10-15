<?php
declare(strict_types=1);

namespace app\common\repository;

use yii\base\BaseObject;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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