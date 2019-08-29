<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticUrlGroupDo;

class StaticUrlGroupEntity extends StaticUrlGroupDo
{
    /**
     * 获取默认一级分组
     * @return array
     * @author zhuozhen
     */
    public  function getDefaultGroup() : array
    {
        return self::find()->select(['groupname','id'])->where(['=','parent',0])->orderBy('groupname')->all();
    }

    /**
     * 获取二级分组Id
     * @param int $firstGroup
     * @return array
     * @author zhuozhen
     */
    public function getSecondGroup(int $firstGroup) : array
    {
        if ($firstGroup !== 0){
            return array_column(self::find()->select('id')->where(['=','parent',$firstGroup])->asArray()->all(),'id');
        }
        return array_column(self::find()->select('id')->where(['!=','parent',0])->asArray()->all(),'id');
    }
}