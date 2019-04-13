<?php

namespace app\modules\v1\userAction\service\impl;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticServiceConversionsPo;
use app\modules\v1\userAction\domain\po\StaticUrlPo;
use app\modules\v1\userAction\service\StaticServiceConversionsService;
use yii\db\Exception;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
class StaticServiceConversionsImpl implements StaticServiceConversionsService
{
    /* @var StaticServiceConversionsPo */
    private static $staticServiceConversions = StaticServiceConversionsPo::class;

    /**
     * increased conversions
     *
     * @param StaticUrlPo $staticUrl
     * @return void
     * @throws Exception
     * @throws ValidateException
     * @author: lirong
     */
    public static function increasedConversions($staticUrl): void
    {
        $staticServiceConversions = self::findOne(['u_id' => $staticUrl->id]);
        if (!$staticServiceConversions) {
            throw new Exception('找不到转化信息!');
        }
        $staticServiceConversions->conversions++;
        $staticServiceConversions->conversions_time = time();
        $staticServiceConversions->save();
        if (!$staticServiceConversions->save()) {
            throw new ValidateException($staticServiceConversions, '表单参数校验异常！', 302);
        }
    }

    /**
     * find one
     *
     * @param mixed $condition
     * @return StaticServiceConversionsPo|null|mixed
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticServiceConversions::findOne($condition);
    }
}
