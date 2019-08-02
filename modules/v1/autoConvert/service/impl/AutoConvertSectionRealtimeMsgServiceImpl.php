<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;


use app\models\dataObject\SectionRealtimeMsgDo;
use app\modules\v1\autoConvert\service\AutoConvertSectionRealtimeMsgService;
use yii\base\BaseObject;

/**
 * Class AutoConvertSectionRealtimeMsgServiceImpl
 * @package app\modules\v1\autoConvert\service\impl
 */
class AutoConvertSectionRealtimeMsgServiceImpl extends BaseObject implements AutoConvertSectionRealtimeMsgService
{
    /** @var SectionRealtimeMsgDo  */
    private $sectionRealtimeMsgDo;

    /**
     * UserActionUserActionStaticConversionImpl constructor.
     *
     * @param SectionRealtimeMsgDo $sectionRealtimeMsgDo
     * @param array                $config
     */
    public function __construct(SectionRealtimeMsgDo $sectionRealtimeMsgDo, $config = [])
    {
        $this->sectionRealtimeMsgDo = $sectionRealtimeMsgDo;
        parent::__construct($config);
    }


    /**
     * @param $condition
     * @return mixed
     * @author zhuozhen
     */
    public function findOne($condition)
    {
        $this->sectionRealtimeMsgDo::findOne($condition);
    }

    /**
     * @param $condition
     * @return mixed
     * @author zhuozhen
     */
    public function findAll($condition)
    {
        $this->sectionRealtimeMsgDo::findAll($condition);
    }
}