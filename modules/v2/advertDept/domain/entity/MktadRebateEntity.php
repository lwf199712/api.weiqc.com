<?php
declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\entity;


use app\models\dataObject\MktadRebateDo;

class MktadRebateEntity extends MktadRebateDo
{
    /**
     * 根据id查询返点表信息
     * @param array  $id
     * @param string $field
     * @return array
     * @author dengkai
     * @date   2019/9/28
     */
    public function selectRebateDataById(array $id, string $field = 'id'): array
    {
        $res = self::find()
            ->select($field)
            ->where(['id' => $id])
            ->asArray()
            ->all();

        $lists = [];
        foreach ($res as $val) {
            $lists[$val['id']] = $val;
        }

        return $lists;
    }
}