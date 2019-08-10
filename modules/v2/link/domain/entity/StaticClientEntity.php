<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;


use app\models\dataObject\StaticClientDo;
use app\modules\v2\link\domain\dto\StaticUrlReportDto;

class StaticClientEntity extends StaticClientDo
{

    public function queryByStaticUrl(StaticUrlReportDto $staticUrlReportDto)
    {
        return self::find()->select(['u_id','ip','date','createtime'])
            ->where(['u_id' => $staticUrlReportDto->id])
            ->andFilterWhere(['between','createtime',$staticUrlReportDto->getBeginDate(),$staticUrlReportDto->getEndDate()])
            ->all();
    }
}