<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\enum;


class TimeRange
{
    /** @var int 今天 */
    public const TODAY = 0;
    /** @var int 昨天 */
    public const YESTERDAY = 1;
    /** @var int 最近七天 */
    public const LAST_SEVEN_DAYS = 2;
    /** @var int 最近三十天 */
    public const LAST_THIRTY_DAYS = 3;
    /** @var int 本月 */
    public const CURRENT_MONTH = 4;
}