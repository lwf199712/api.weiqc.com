<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\entity\StaticUrlEntity as StaticListAggregateRoot;
use yii\data\ActiveDataProvider;

class StaticUrlDoManager extends BaseRepository
{
    public static $modelClass = StaticUrlDo::class;

    public function listDataProvider(StaticUrlDto $staticUrlDto,StaticListAggregateRoot $staticListAggregateRoot): ActiveDataProvider
    {
        $this->query
            ->alias('staticUrl')
            ->select(['staticUrl.id', 'staticUrl.ident', 'staticUrl.url', 'staticUrl.pcurl', 'staticUrl.name', 'staticUrl.conversion_cost', 'staticUrl.group_id', 'staticUrl.m_id' ,
                'member.username',
                'staticUrlGroup.groupname','staticUrlGroup.desc'])
            ->andWhere(['BETWEEN', 'createtime', $staticUrlDto->beginDate, $staticUrlDto->endDate])
            ->joinWith(['member','staticUrlGroup']);

        if ($staticUrlDto->fieldValue === 'username') {     //username的情况需特殊处理
            $this->query->andWhere(['like', 'member.username', $staticUrlDto->fieldValue]);
        } else {
            $this->query->andFilterWhere(['=', 'staticUrl.' . $staticUrlDto->field, $staticUrlDto->fieldValue]);
        }

        if ($staticUrlDto->userName || $staticUrlDto->channelName) {         //负责人|渠道
            $this->query
                ->andFilterWhere(['=', 'staticUrlGroup.user_name', $staticUrlDto->userName])
                ->andFilterWhere(['=', 'staticUrlGroup.channel_name', $staticUrlDto->channelName]);
        }

        if ($staticUrlDto->service) {                                        //服务号
            $this->query->joinWith('staticServiceConversions')
                ->andWhere(['=', 'staticServiceConversions.service', $staticUrlDto->service]);
        }

        if ($staticUrlDto->secondGroup) {                                    //分组
            $this->query->andWhere(['=', 'staticUrl.group_id', $staticUrlDto->secondGroup]);
        } else {
            $this->query->andWhere(['in', 'staticUrl.group_id', $staticListAggregateRoot->getAllSecondGroup((int)$staticUrlDto->firstGroup)]);
        }

        $this->query->andWhere(['=', 'recycle', $staticUrlDto->recycle]);

        return new ActiveDataProvider([
            'query'      => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $params['perPage'] ?? 10,
            ],
            'sort'       => [
                'attributes'   => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }

}