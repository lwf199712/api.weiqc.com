<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StaticUrlDo;

class StaticUrlEntity extends StaticUrlDo
{

    /**
     * 更新统计链接实体
     * @param StaticUrlEntity $staticUrlEntity
     * @param string          $service
     * @return void
     * @author zhuozhen
     */
    public function updateEntity(StaticUrlEntity $staticUrlEntity, string $service): void
    {
        if (strpos($staticUrlEntity->url, 'wxh') && strpos($staticUrlEntity->pcurl, 'wxh')) {
            $staticUrlEntity->url   = substr($staticUrlEntity->url, 0, strrpos($staticUrlEntity->url, '?')) . '?wxh=' . $service;
            $staticUrlEntity->pcurl = substr($staticUrlEntity->pcurl, 0, strrpos($staticUrlEntity->pcurl, '?')) . '?wxh=' . $service;
            $staticUrlEntity->save();
        }
    }
}