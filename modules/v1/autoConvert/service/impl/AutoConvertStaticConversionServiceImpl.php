<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;

use app\models\dataObject\StaticServiceConversionsDo;
use app\modules\v1\autoConvert\service\AutoConvertStaticConversionService;
use phpDocumentor\Reflection\Types\Boolean;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class AutoConvertStaticConversionServiceImpl
 * @property StaticServiceConversionsDo $staticServiceConversionsDo
 * @package app\modules\v1\autoConvert\service\impl
 */
class AutoConvertStaticConversionServiceImpl extends BaseObject implements AutoConvertStaticConversionService
{
    /** @var StaticServiceConversionsDo */
    public $staticServiceConversionsDo;

    public function __construct(StaticServiceConversionsDo $staticServiceConversionsDo,$config = [])
    {
        $this->staticServiceConversionsDo = $staticServiceConversionsDo;
        parent::__construct($config);
    }


    /**
     * 切换公众号时更新service字段
     * @param int    $id
     * @param string $service
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateService(int $id, string $service): bool
    {
        $row = $this->staticServiceConversionsDo::updateAll(['service' => $service],['id' => $id]);
        if ($row < 1){
            Yii::info('更新表bm_statis_service_conversions的service字段失败');
            throw new Exception('更新表bm_statis_service_conversions的service字段失败');
        }
        return true;
    }
}