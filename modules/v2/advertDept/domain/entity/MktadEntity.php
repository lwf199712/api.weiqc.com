<?php


namespace app\modules\v2\advertDept\domain\entity;

use app\models\dataObject\MktadDo;
use app\models\dataObject\MktadUserDo;
use app\modules\v2\advertDept\domain\dto\VideoStatisticsDto;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class MktadEntity extends MktadDo
{

    /**
     * 查询视频测试详情首页投放数据
     * @param VideoStatisticsDto $videoStatisticsDto
     * @param string             $field
     * @param array              $videoId
     * @param array              $queryTime
     * @param string             $groupBy
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/9/27
     */
    public function selectPutData(VideoStatisticsDto $videoStatisticsDto, string $field, array $videoId, array $queryTime, string $groupBy = ''): ActiveQuery
    {

        $query = self::find()
            ->alias('m')
            ->select($field)
            ->joinWith(['mktadDetail md', 'mktadUser u'])
            ->where(['md.v_id' => $videoId])
            ->andWhere(['between', 'm.write_time', $queryTime['beginTime'], $queryTime['endTime']])
            ->andWhere(['m.is_delete' => 0])
            ->andFilterWhere([
                'u.username' => $videoStatisticsDto->follower,
                'md.number'  => $videoStatisticsDto->number
            ]);

        if (!empty($videoStatisticsDto->serviceId)) {
            $query->joinWith('mktadService s')
                ->andWhere(['m.s_id' => $videoStatisticsDto->serviceId]);
        }

        if (!empty($groupBy)) {
            $query->groupBy($groupBy);
        }

        return $query;
    }

    /**
     * 获取数据提供器
     * @param ActiveQuery        $query
     * @param array              $sort
     * @param VideoStatisticsDto $videoStatisticsDto
     * @return ActiveDataProvider
     * @author dengkai
     * @date   2019/9/27
     */
    public function getActiveDataProvider(ActiveQuery $query, VideoStatisticsDto $videoStatisticsDto, array $sort = []): ActiveDataProvider
    {
        $parameter = [
            'query'      => $query,
            'pagination' => ['pageSize' => $videoStatisticsDto->perPage]
        ];

        if (!empty($sort)) {
            $parameter['sort'] = ['attributes' => array_keys($sort), 'defaultOrder' => $sort];
        }

        return new ActiveDataProvider($parameter);
    }

    /**
     * 查询视频统计首页投放数据
     * @param VideoStatisticsDto $videoStatisticsDto
     * @param string             $field
     * @param array              $queryTime
     * @param string             $groupBy
     * @return ActiveQuery
     * @author dengkai
     * @date   2019/10/6
     */
    public function selectVideoStatisticsPutData(VideoStatisticsDto $videoStatisticsDto, string $field, array $queryTime, string $groupBy = ''): ActiveQuery
    {

        $query = self::find()
            ->alias('m')
            ->select($field)
            ->joinWith(['mktadDetail md', 'mktadVideo v'])
            ->where(['between', 'm.write_time', $queryTime['beginTime'], $queryTime['endTime']])
            ->andWhere(['m.is_delete' => 0])
            ->andFilterWhere(['like', 'v.video_name', $videoStatisticsDto->videoName]);

        if (!empty($videoStatisticsDto->serviceId)) {
            $query->joinWith('mktadService s')
                ->andWhere(['m.s_id' => $videoStatisticsDto->serviceId]);
        }

        if (!empty($groupBy)) {
            $query->groupBy($groupBy);
        }

        return $query;
    }

}