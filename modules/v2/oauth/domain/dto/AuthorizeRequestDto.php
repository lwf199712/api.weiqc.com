<?php
declare(strict_types=1);

namespace app\modules\v2\oauth\domain\dto;

use yii\base\Model;

/**
 * Class AuthorizeRequestDto
 * @property int    $app_id
 * @property string $state
 * @property string $scope
 * @property string $redirect_uri
 *
 * @package app\modules\v2\oauth\dto
 */
class AuthorizeRequestDto extends Model
{
    /* @var int app_id */
    public $app_id;
    /* @var string $state */
    public $state;
    /* @var string $scope */
    public $scope;
    /** @var string $redirect_uri */
    public $redirect_uri;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     * @author: lirong
     */
    public function rules(): array
    {
        return [
            [['app_id', 'redirect_uri'], 'required'],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     * @author: lirong
     */
    public function attributeLabels(): array
    {
        return [
            'app_id'       => '应用ID',
            'state'        => '自定义参数',
            'scope'        => '授权范围',
            'redirect_uri' => '回调链接',
        ];
    }
}