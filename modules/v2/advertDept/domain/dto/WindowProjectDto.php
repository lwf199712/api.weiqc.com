<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/12
 * Time: 14:14
 */

namespace app\modules\v2\advertDept\domain\dto;


use yii\base\Model;

class WindowProjectDto extends Model
{
    public const SEARCH = 'search';
    public const DELETE = 'delete';
    public const EXPORT = 'export';
    public const READ = 'read';

    public $scenario;

    /** @var int */
    public $id;
    /** @var string */
    public $product_name;
    /** @var int */
    public $data_time;
    /** @var int */
    public $period;
    /** @var string */
    public $account_and_id;
    /** @var string */
    public $delivery_platform;
    /** @var string */
    public $video_name;
    /** @var string */
    public $consume;
    /** @var string */
    public $total_turnover;
    /** @var string */
    public $real_turnover;
    /** @var string */
    public $transaction_data;
    /** @var string */
    public $responsible_person;
    /** @var int */
    public $create_at;
    /** @var int */
    public $beginTime;
    /** @var int */
    public $endTime;
    /** @var int */
    public $page;
    /** @var int */
    public $perPage;

    public function rules():array
    {
        return [
            [['id','page','perPage'], 'integer'],
            ['delivery_platform', 'in', 'range' => ['MVEBackstage', 'WISBackstage', 'WISXiaoXi']],
            [['endTime','beginTime','account_and_id','video_name','delivery_platform','period','product_name'], 'string', 'on' => self::SEARCH],
            ['id', 'integer', 'on' => self::READ],
            ['id', 'required', 'on' => self::READ],
            ['id', 'integer', 'on' => self::DELETE],
            ['id', 'required', 'on' => self::DELETE],
            [['data_time','account_and_id','delivery_platform','product_name'], 'string', 'on' => self::EXPORT],
            [['data_time','account_and_id','delivery_platform','product_name'], 'required', 'on' => self::EXPORT],
            ['scenario', 'in', 'range' => [self::SEARCH,self::READ,self::EXPORT,self::DELETE], 'message' => '???????????????']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            //-----------??????--------------
            'endTime' => '????????????',
            'beginTime' => '????????????',
            'account_and_id' => '??????+??????ID',
            'video_name' => '????????????',
            'delivery_platform' => '????????????',
            'period' => '?????????',
            'product_name' => '????????????',
            //-----------??????--------------
            'data_time' => '??????',
            //-----------??????--------------
            //-----------??????--------------
            'id' => 'ID',
        ];
    }

    public function fields(): array
    {
        switch ($this->getScenario()) {
            case self::SEARCH :
                return parent::fields();
            case self::READ :
                return ['id'];
            case self::EXPORT :
                return ['data_time','account_and_id','delivery_platform','product_name'];
            case self::DELETE :
                return ['id'];
            default:
                return parent::fields();
        }
    }

    public function setScenario($value)
    {
        parent::setScenario($value); // TODO: Change the autogenerated stub
        return $this;
    }
}