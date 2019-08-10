<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;

use app\models\dataObject\StaticServiceConversionsDo;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\autoConvert\service\AutoConvertStaticUrlService;
use Exception;
use RuntimeException;
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

    public function __construct(StaticUrlDo $staticUrlDo, $config = [])
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
    public function getServiceUrl(string $currentDept): array
    {
        $todayBegin = strtotime(date('Y-m-d'));
        $todayEnd   = strtotime('+1 day',$todayBegin) - 1;

        $urlSet     = $this->staticUrlDo::find()
            ->alias('u')
            ->select('u.id as url_id,s.id as service_id,s.service,u.url,u.pcurl')
            ->leftJoin(StaticServiceConversionsDo::tableName() . ' as s', 'u.id = s.u_id')
            ->where(['s.pattern' => 3, 's.service' => $currentDept])
            ->andWhere(['between', 's.conversions_time', $todayBegin, $todayEnd])
            ->asArray()
            ->all();
        return $urlSet;
    }

    /**
     *  更新url和pcUrl字段
     * @param int    $id
     * @param string $url
     * @param string $pcUrl
     * @param string $oldDept
     * @param string $newDept
     * @return int
     * @throws Exception
     * @author zhuozhen
     */
    public function updateUrl(int $id, string $url, string $pcUrl, string $oldDept, string $newDept): int
    {
        $row = $this->staticUrlDo::updateAll(['url' => $url, 'pcurl' => $pcUrl], ['id' => $id]);
        if ($row < 1) {
            throw new RuntimeException("statis_url表将公众号 $oldDept 切换为 $newDept 时出错，url和pcurl字段更新失败！");
        }
        return $row;
    }

    /**
     * 获取特定模式下除去某公众号对应的url
     * @param string $currentDept
     * @return array
     * @author dengkai
     * @date 2019-08-08
     */
    public function getServiceUrlExceptSomeOne(string $currentDept): array
    {
        $todayBegin = strtotime(date('Y-m-d'));
        $todayEnd   = strtotime('+1 day',$todayBegin) - 1;

        $urlSet     = $this->staticUrlDo::find()
            ->alias('u')
            ->select('u.id as url_id,s.id as service_id,s.original_service,u.url,u.pcurl')
            ->leftJoin(StaticServiceConversionsDo::tableName() . ' as s', 'u.id = s.u_id')
            ->where(['s.pattern' => 3])
            ->andWhere(['!=','s.original_service',$currentDept])
            ->andWhere(['between', 's.conversions_time', $todayBegin, $todayEnd])
            ->asArray()
            ->all();
        return $urlSet;
    }
}