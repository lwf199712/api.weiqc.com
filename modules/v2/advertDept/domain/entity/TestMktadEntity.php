<?php


namespace app\modules\v2\advertDept\domain\entity;


use app\models\dataObject\BigDataStatisticsDo;
use app\models\dataObject\TestMktadDetailDo;
use app\models\dataObject\TestMktadDo;
use app\modules\v2\advertDept\domain\dto\VideoStatisticsDto;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

class TestMktadEntity extends TestMktadDo
{
    /**
     * 根据编号（测试/大投）查询测试数据信息
     * @param array $field
     * @param array $number
     * @param array $queryTime
     * @param bool  $isTestNumber
     * @return array
     * @author dengkai
     * @date   2019/9/29
     */
    public function selectTestMktadData(array $field, array $number, array $queryTime, bool $isTestNumber): array
    {
        //QB形式查询数据
        $query = (new Query())->from(TestMktadDo::tableName() . ' as m')
            ->select($field)
            ->leftJoin(TestMktadDetailDo::tableName() . ' as md', 'm.id = md.t_id')
            ->where(['between', 'md.create_time', $queryTime['beginTime'], $queryTime['endTime']])
            ->andWhere(['m.is_delete' => 0]);

        /*$query = self::find()
            ->alias('m')
            ->select($field)
            ->joinWith('testMktadDetail md')
            ->where(['between', 'md.create_time', $queryTime['beginTime'], $queryTime['endTime']])
            ->andWhere(['m.is_delete' => 0]);*/

        if ($isTestNumber) {
            $query->andWhere(['m.test_number' => $number]);
        } else {
            //            $query->joinWith(['bigDataStatistics b'])->andWhere(['b.delivery_number' => $number]);
            $query->leftJoin(BigDataStatisticsDo::tableName() . 'as b', 'm.test_number = b.test_number')->andWhere(['b.delivery_number' => $number]);
        }

//        $lists = $query->asArray()->all();
        $lists = $query->all();
        return $lists;
    }
}