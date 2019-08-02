<?php

namespace app\modules\v1\userAction\service\impl;

use app\models\dataObject\StaticUrlDo;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use yii\base\BaseObject;

/**
 * Interface ConversionService
 *
 * @property StaticUrlDo $staticUrl
 * @author: lirong
 */
class UserActionUserActionStaticUrlImpl extends BaseObject implements UserActionStaticUrlService
{
    /* @var StaticUrlDo */
    private $staticUrl;

    public function __construct(StaticUrlDo $staticUrl, $config = [])
    {
        $this->staticUrl = $staticUrl;
        parent::__construct($config);
    }

    /**
     * find one
     *
     * @param mixed $condition
     * @param null $select
     * @return StaticUrlDo|mixed|null
     * @author: lirong
     */
    public function findOne($condition, $select = null)
    {
        if ($select === null) {
            return $this->staticUrl::findOne($condition);
        }
        return $this->staticUrl::find()->select($select)->where($condition)->one();
    }

    /**
     * 更新链接
     * @param int    $id
     * @param string $urlService
     * @return bool
     * @author zhuozhen
     */
    public function updateService(int $id, string $urlService): bool
    {
        $urlInfo = $this->staticUrl::findOne(['id' => $id]);
        if ($urlInfo === null || empty($urlService)){
            return false;
        }
        if (strpos($urlInfo->url, 'wxh')) {
            $url = substr($urlInfo->url, 0, strrpos($urlInfo->url, '?'));
        }
        $url = $url . '?wxh=' . $urlService;
        if (strpos($urlInfo->pcurl, 'wxh')) {
            $pcurl = substr($urlInfo->pcurl, 0, strrpos($urlInfo->pcurl, '?'));
        }
        $pcurl = $pcurl . '?wxh=' . $urlService;

        $result = $this->staticUrl::updateAll(['url' => $url, 'pcurl' => $pcurl],['id' => $id]);
        return $result > 0;
    }
}
