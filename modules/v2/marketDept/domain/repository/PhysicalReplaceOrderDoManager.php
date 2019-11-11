<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\repository;

use app\common\repository\BaseRepository;
use app\models\dataObject\PhysicalReplaceOrderDo;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderQuery;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class PhysicalReplaceOrderDoManager extends BaseRepository
{
    /** @var string 资源类名 */
    public static $modelClass = PhysicalReplaceOrderDo::class;

    public function listDataProvider(PhysicalReplaceOrderQuery $physicalReplaceOrderQuery): ActiveDataProvider
    {
        $this->query
            ->andFilterWhere(['>', 'dispatch_time',     $physicalReplaceOrderQuery->beginTime])
            ->andFilterWhere(['<', 'dispatch_time',     $physicalReplaceOrderQuery->endTime])
            ->andFilterWhere(['=', 'first_trial',       $physicalReplaceOrderQuery->first_trial])
            ->andFilterWhere(['=', 'final_judgment',    $physicalReplaceOrderQuery->final_judgment])
            ->andFilterWhere(['=', 'prize_send_status', $physicalReplaceOrderQuery->prize_send_status])
            ->andFilterWhere(['=', 'we_chat_id',        $physicalReplaceOrderQuery->we_chat_id])
            ->andFilterWhere(['=', 'nick_name',         $physicalReplaceOrderQuery->nick_name])
            ->andFilterWhere(['=', 'follower',          $physicalReplaceOrderQuery->follower])
            ->andFilterWhere(['=', 'replace_product',   $physicalReplaceOrderQuery->replace_product]);
        //post_status为0是未发文，1是已发文
        if ($physicalReplaceOrderQuery->post_status == '0') {
            $this->query->andWhere(['=', 'put_link', '']);
        } else if ($physicalReplaceOrderQuery->post_status == '1') {
            $this->query->andWhere(['NOT', ['put_link' => '']]);
        }


        $perPage = $physicalReplaceOrderQuery->getPerPage();
        return new ActiveDataProvider([
            'query' => $this->query->asArray(),
            'pagination' => [
                'pageSize' => $perPage ?? 10,
            ],
            'sort' => [
                'attributes' => [
                    'dispatch_time' => [
                        'asc'   => ['dispatch_time' => SORT_ASC],
                        'desc'  => ['dispatch_time' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ],
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
    }

    /**
     * 根据id获取一条记录
     * @param $id
     * @return ActiveRecord
     * @author weifeng
     */

    public function findOne($id)
    {
        return $this->model::findOne(['id' => $id]);
    }

    /**
     * 批量更新sql
     * @param string $table          表名
     * @param array  $columns        更新的字段名
     * @param array  $rows           更新的字段值
     * @param array  $primaryArrays  主键值
     * @param string $primaryColumn  主键名
     * @return string
     * @author weifeng
     */
    public function getBatchUpdateSql(string $table,array $columns,array $rows, array $primaryArrays,string $primaryColumn)
    {
        $sql = sprintf('UPDATE %s SET ', $table);
        $flagColumn = 0;
        foreach ($columns as $column) {
            $flag = 0;
            $sql .= sprintf("%s = CASE %s", $column, $primaryColumn);
            foreach ($rows as $row) {
                $row = array_values($row);
                $id = $primaryArrays[$flag];
                $value = $row[$flagColumn];
                $sql .= sprintf(' WHEN %s THEN %s', $id, $value);
                $flag++;
            }
            $sql .= ' END,';
            $flagColumn++;
        }
        return rtrim($sql, ',');
    }
}