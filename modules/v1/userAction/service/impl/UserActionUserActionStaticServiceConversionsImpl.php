<?php

namespace app\modules\v1\userAction\service\impl;

use app\common\exception\ValidateException;
use app\models\dataObject\StaticServiceConversionsDo;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\userAction\service\UserActionStaticServiceConversionsService;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Interface ConversionService
 *
 * @property StaticServiceConversionsDo $staticServiceConversions
 * @author: lirong
 */
class UserActionUserActionStaticServiceConversionsImpl extends BaseObject implements UserActionStaticServiceConversionsService
{
    /* @var StaticServiceConversionsDo */
    private $staticServiceConversions;

    /**
     * UserActionUserActionStaticServiceConversionsImpl constructor.
     *
     * @param StaticServiceConversionsDo $staticServiceConversions
     * @param array $config
     */
    public function __construct(StaticServiceConversionsDo $staticServiceConversions, $config = [])
    {
        $this->staticServiceConversions = $staticServiceConversions;
        parent::__construct($config);
    }

    /**
     * increased conversions
     *
     * @param StaticUrlDo $staticUrl
     * @return void
     * @throws Exception
     * @throws ValidateException
     * @author: lirong
     */
    public function increasedConversions(StaticUrlDo $staticUrl): void
    {
        $staticServiceConversions = $this->staticServiceConversions::findOne(['u_id' => $staticUrl->id]);
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
     * @return StaticServiceConversionsDo|null|mixed
     * @author: lirong
     */
    public function findOne($condition)
    {
        return $this->staticServiceConversions::findOne($condition);
    }
}
