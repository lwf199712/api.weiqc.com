<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\repository;

use app\common\repository\BaseRepository;
use yii\data\ActiveDataProvider;
use app\models\dataObject\StatisticsUrlGroupChannelDo;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelQuery;

/**
 * Class StatisticsUrlGroupChannelDoManager
 * @package app\modules\v2\link\domain\repository
 */
class StatisticsUrlGroupChannelDoManager extends BaseRepository
{
    /**
     * @var string 资源类名
     */
    public static $modelClass = StatisticsUrlGroupChannelDo::class;

    public function listDataProvider(StatisticsUrlGroupChannelQuery $statisticsUrlGroupChannelQuery): ActiveDataProvider
    {
        $this->query
            ->select('id, channel_name, creator, created_at')
            ->andWhere(['=', 'is_delete', 0])
            ->andFilterWhere(['=', 'id', $statisticsUrlGroupChannelQuery->id])
            ->andFilterWhere(['=', 'channel_name', $statisticsUrlGroupChannelQuery->channel_name])
            ->andFilterWhere(['like', 'channel_name', $statisticsUrlGroupChannelQuery->channel_name]);

        $perPage = $statisticsUrlGroupChannelQuery->perPage;

        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $perPage ?? 10,
            ],
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
    }

}
