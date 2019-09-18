<?php declare(strict_types=1);


namespace app\modules\v2\advertDept\domain\dto;


use yii\base\Model;

/**
 * Class TmallOrderDto
 * @property-read int $start_at
 * @property-read int $end_at
 * @package app\modules\v2\advertDept\domain\dto
 */
class TmallOrderDto extends Model
{

    private $since;


    public function rules(): array
    {
        return [
            ['since','required'],
            ['since','integer'],
        ];
    }



    public function attributeLabels(): array
    {
        return [
            'since' => '从此刻及此刻之后开始拉取',
        ];
    }

    /**
     * @return mixed
     */
    public function getSince() : int
    {
        return $this->since;
    }

    /**
     * @param mixed $since
     */
    public function setSince(int $since): void
    {
        $this->since = $since;
    }

}