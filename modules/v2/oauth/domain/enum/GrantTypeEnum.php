<?php
declare(strict_types=1);

namespace app\modules\v2\oauth\domain\enum;


abstract class GrantTypeEnum
{

    public const REFRESH_TOKEN = 'refresh_token';

    public const AUTH_CODE = 'auth_code';
}