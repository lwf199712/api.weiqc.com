<?php
declare(strict_types=1);

namespace app\modules\v2\link\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\StaticUrlDo;
use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\entity\StaticUrlGroupEntity;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class StaticUrlDoManager extends BaseRepository
{
    /** @var string  资源类名 */
    public static $modelClass = StaticUrlDo::class;

    /**
     * @param int $staticUrlId
     * @return ActiveQuery
     * @author zhuozhen
     */
    public function viewData(int $staticUrlId): ActiveQuery
    {
        return $this->query
            ->alias('staticUrl')
            ->select(['staticUrl.id', 'staticUrl.ident', 'staticUrl.url', 'staticUrl.pcurl', 'staticUrl.name', 'staticUrl.group_id', 'staticUrl.m_id',
                'member.username',])
            ->where(['=', 'staticUrl.id', $staticUrlId])
            ->joinWith(['member'])
            ->one();
    }

    /**
     * @param StaticUrlDto         $staticUrlDto
     * @param StaticUrlGroupEntity $staticUrlGroupEntity
     * @return ActiveDataProvider
     * @author zhuozhen
     */
    public function listDataProvider(StaticUrlDto $staticUrlDto, StaticUrlGroupEntity $staticUrlGroupEntity): ActiveDataProvider
    {
        $this->query
            ->alias('staticUrl')
            ->select(['staticUrl.id', 'staticUrl.ident', 'staticUrl.url', 'staticUrl.pcurl', 'staticUrl.name', 'staticUrl.group_id', 'staticUrl.m_id',
                'member.username',
                'staticUrlGroup.groupname', 'staticUrlGroup.desc'])
            ->andWhere(['BETWEEN', 'staticUrl.createtime', $staticUrlDto->getBeginDate(), $staticUrlDto->getEndDate()])
            ->joinWith(['member', 'staticUrlGroup']);


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
            $this->query->andWhere(['in', 'staticUrl.group_id', $staticUrlGroupEntity->getSecondGroup((int)$staticUrlDto->firstGroup)]);
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