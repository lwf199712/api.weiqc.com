<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;


use app\models\dataObject\StaticServiceConversionsDo;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\autoConvert\service\AutoConvertStaticUrlService;
use yii\base\BaseObject;

/**
 * Class AutoConvertStaticUrlServiceImpl
 * @property StaticUrlDo $staticUrlDo
 * @package app\modules\v1\autoConvert\service\impl
 */
class AutoConvertStaticUrlServiceImpl extends BaseObject implements AutoConvertStaticUrlService
{
    /** @var StaticUrlDo */
    public $staticUrlDo;

    public function __construct(StaticUrlDo $staticUrlDo,$config = [])
    {
        $this->staticUrlDo = $staticUrlDo;
        parent::__construct($config);
    }

    /**
     * 获取特定模式下公众号对应的url
     * @param string $currentDept
     * @return array
     * @author zhuozhen
     */
    public function getServiceUrl(string $currentDept) : array
    {
        $todayBegin = strtotime(date('Y-m-d'));
        $todayEnd = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        $urlSet = $this->staticUrlDo::find()
            ->alias('u')
            ->select('u.id as url_id,s.id as service_id,s.service,u.url,u.pcurl')
            ->leftJoin(StaticServiceConversionsDo::tableName() . 'as s','u.id = s.u_id')
            ->where(['s.pattern' => 3 , 's.service' => $currentDept])
            ->andWhere(['between','s.conversions_time',$todayBegin,$todayEnd])
            ->asArray()
            ->all();
        return $urlSet;
    }

    /**
     * @param int    $id
     * @param string $url
     * @param string $pcUrl
     * @param string $oldDept
     * @param string $newDept
     * @return array
     * @author zhuozhen
     */
    public function updateUrl(int $id, string $url, string $pcUrl, string $oldDept, string $newDept): array
    {
        // TODO: Implement updateUrl() method.
    }
}