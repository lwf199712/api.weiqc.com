<?php
declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\entity;


use app\models\dataObject\MktadServiceDo;

class MktadServiceEntity extends MktadServiceDo
{
    /**
     * 查询所有的服务号
     * @return array
     * @author dengkai
     * @date   2019/10/7
     */
    public function selectAllService():array
    {
        $lists = self::find()
            ->select('id,service')
            ->where(['is_delete'=>0])
            ->asArray()
            ->all();

        return $lists;
    }
}