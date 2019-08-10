<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\enum;


class Pattern
{
    /** @var int 不循环 */
    public const NOT_CIRCLE = 0;
    /** @var int 每小时循环 */
    public const CIRCLE_BY_HOURS = 1 ;
    /** @var int 按转换数循环 */
    public const CIRCLE_BY_NUM = 2;
    /** @var int 自动循环 */
    public const AUTO_CONVERSION = 3;
}