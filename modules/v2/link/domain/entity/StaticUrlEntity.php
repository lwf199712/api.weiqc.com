<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StaticUrlDo;

class StaticUrlEntity extends StaticUrlDo
{
    /**
     * 获取一级分组下的二级分组
     * @param int $firstGroup
     * @return array
     * @author zhuozhen
     */
    public function getAllSecondGroup(int $firstGroup): array
    {
        return array_column(self::find()->joinWith('staticUrlGroup')->select('staticUrlGroup.id')->where(['in', 'staticUrlGroup.parent', $firstGroup])->all(), 'id') ?? [$firstGroup];
    }

    /**
     * 更新公众号链接
     * @param StaticUrlEntity $staticUrlEntity
     * @param string          $service
     * @return void
     * @author zhuozhen
     */
    public function updateUrl(StaticUrlEntity $staticUrlEntity, string $service): void
    {
        if (strpos($staticUrlEntity->url, 'wxh') && strpos($staticUrlEntity->pcurl, 'wxh')) {
            $staticUrlEntity->url   = substr($staticUrlEntity->url, 0, strrpos($staticUrlEntity->url, '?')) . '?wxh=' . $service;
            $staticUrlEntity->pcurl = substr($staticUrlEntity->pcurl, 0, strrpos($staticUrlEntity->pcurl, '?')) . '?wxh=' . $service;
            $staticUrlEntity->save();
        }
    }
}