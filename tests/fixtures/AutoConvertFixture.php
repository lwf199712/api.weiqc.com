<?php
declare(strict_types=1);

namespace fixtures;

use yii\test\ActiveFixture;

class AutoConvertFixture extends ActiveFixture
{
    public $modelClass = 'modules\v1\autoConvert\domain\vo\ConvertRequestVo';
}