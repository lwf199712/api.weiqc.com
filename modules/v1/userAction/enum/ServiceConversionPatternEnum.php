<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\enum;

/** 落地页公众号转换模式 */
abstract class ServiceConversionPatternEnum
{
    /** @var int 不循环模式 */
    public const NOT_CIRCLE_PATTERN = 0;
    /** @var int 按小时循环模式 */
    public const HOUR_CIRCLE_PATTERN = 1;
    /** @var int 按转化数循环模式 */
    public const TURNOVER_NUMBER_CIRCLE_PATTERN = 2;
    /** @var int 自动转粉 */
    public const  AUTO_CONVERT_PATTERN = 3;

}