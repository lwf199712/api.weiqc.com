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
    public static function getAllSecondGroup(int $firstGroup) : array
    {
        return array_column(self::find()->joinWith('staticUrlGroup')->select('staticUrlGroup.id')->where(['in','staticUrlGroup.parent',$firstGroup])->all(),'id') ?? [$firstGroup];
    }
}