<?php declare(strict_types=1);

namespace app\modules\v2\marketDept\service\impl;

use app\common\facade\ExcelFacade;
use app\models\dataObject\PhysicalReplaceOrderDo;
use app\models\dataObject\PhysicalSendStatusDo;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderDto;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderForm;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderImport;
use app\modules\v2\marketDept\service\PhysicalReplaceOrderService;
use app\modules\v2\marketDept\domain\dto\PhysicalReplaceOrderQuery;
use app\modules\v2\marketDept\domain\repository\PhysicalReplaceOrderDoManager;
use app\modules\v2\marketDept\domain\entity\PhysicalReplaceOrderEntity;
use Exception;
use RuntimeException;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class PhysicalReplaceOrderImpl extends BaseObject implements PhysicalReplaceOrderService
{
    /** @var PhysicalReplaceOrderDoManager */
    public $physicalReplaceOrderDoManager;
    /** @var PhysicalReplaceOrderQuery */
    public $physicalReplaceOrderQuery;
    /** @var PhysicalReplaceOrderEntity */
    public $physicalReplaceOrderEntity;
    /** @var PhysicalReplaceOrderDto */
    public $physicalReplaceOrderDto;
    /** @var PhysicalReplaceOrderForm */
    public $physicalReplaceOrderForm;
    /** @var ActiveRecord */
    public $model;
    /** @var PhysicalSendStatusDo */
    public $physicalSendStatusDo;

    public function __construct(
        PhysicalReplaceOrderDoManager   $physicalReplaceOrderDoManager,
        PhysicalReplaceOrderQuery       $physicalReplaceOrderQuery,
        PhysicalReplaceOrderDo          $physicalReplaceOrderDo,
        PhysicalSendStatusDo            $physicalSendStatusDo,
        PhysicalReplaceOrderEntity      $physicalReplaceOrderEntity,
        PhysicalReplaceOrderDto         $physicalReplaceOrderDto,
        PhysicalReplaceOrderForm        $physicalReplaceOrderForm,
        $config = [])
    {
        $this->physicalReplaceOrderDoManager = $physicalReplaceOrderDoManager;
        $this->physicalReplaceOrderQuery     = $physicalReplaceOrderQuery;
        $this->model                         = $physicalReplaceOrderDo;
        $this->physicalSendStatusDo          = $physicalSendStatusDo;
        $this->physicalReplaceOrderEntity    = $physicalReplaceOrderEntity;
        $this->physicalReplaceOrderDto       = $physicalReplaceOrderDto;
        $this->physicalReplaceOrderForm      = $physicalReplaceOrderForm;
        parent::__construct($config);
    }

    /**
     * 订单首页
     * @param PhysicalReplaceOrderQuery $physicalReplaceOrderQuery
     * @return array
     * @author weifeng
     */
    public function listData(PhysicalReplaceOrderQuery $physicalReplaceOrderQuery): array
    {
        $list['lists'] = $this->physicalReplaceOrderDoManager->listDataProvider($physicalReplaceOrderQuery)->getModels();
        //统计数量
        if (!empty($list['lists'])){
            //设置分页统计
            $physicalReplaceOrderQuery->setPerPage(0);
            $listData          = $this->physicalReplaceOrderDoManager->listDataProvider($physicalReplaceOrderQuery)->getModels();
            $list['statistic'] = $this->statisticsData($listData);
        }
        $list['brandArr']   = $this->getBrandArr();
        $list['totalCount'] = $this->physicalReplaceOrderDoManager->listDataProvider($physicalReplaceOrderQuery)->getTotalCount();
        return $list;
    }

    /**
     * 导入订单
     * @param PhysicalReplaceOrderImport $physicalReplaceOrderImport
     * @return int
     * @throws Exception
     * @author weifeng
     */
    public function importReplaceOrder(PhysicalReplaceOrderImport $physicalReplaceOrderImport): int
    {
        $physicalReplaceOrderImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        if ($physicalReplaceOrderImport->excelFile === null) {
            throw new RuntimeException('excel上传文件不能为空');
        }
        $data = ExcelFacade::import($physicalReplaceOrderImport->excelFile->tempName);
        $data = $this->dealImportData($data);
        //不需要的字段
        $unsetData = ['id', 'put_link', 'first_trial', 'final_judgment', 'prize_send_status', 'audit_opinion', 'first_audit_opinion', 'final_audit_opinion', 'first_auditor', 'final_auditor', 'advert_read_num', 'volume_transaction', 'new_fan_attention'];
        return Yii::$app->db->createCommand()->batchInsert('{{%physical_replace_order}}', array_diff($this->model->attributes(), $unsetData), $data)->execute();
    }

    /**
     * 导出订单
     * @param PhysicalReplaceOrderQuery $physicalReplaceOrderQuery
     * @return mixed|void
     * @author weifeng
     */
    public function exportReplaceOrder(PhysicalReplaceOrderQuery $physicalReplaceOrderQuery)
    {
        //不翻页，传perPage为0,page为0,其他条件需要传
        $listData = $this->listData($physicalReplaceOrderQuery);
        //处理首页数据
        $listData = $this->dealExportListData($listData['lists']);
        $tableName = ['实物置换订单数据'];
        $tableHeader = ['nick_name'=>'昵称', 'we_chat_id'=>'微信号', 'fans_amount'=>'粉丝量', 'advert_location'=>'广告位置', 'put_times'=>'投放次数', 'dispatch_time'=>'发文时间', 'follower'=>'跟进人',
            'female_powder_proportion'=>'女粉占比', 'put_link'=>'投放链接', 'replace_product'=>'置换产品', 'replace_quantity'=>'置换件数', 'brand'=>'品牌', 'average_reading'=>'平均阅读量', 'account_type'=>'账号类型', 'first_trial'=>'初审',
            'final_judgment'=>'终审', 'prize_send_status'=>'奖品寄出状态', 'advert_read_num'=>'广告阅读数量', 'volume_transaction'=>'成交额', 'new_fan_attention'=>'新粉丝关注数'];

        return ['exportName' => ExcelFacade::exportExcelFile(array_merge([$tableName],[$tableHeader], $listData), 'PhysicalReplaceOrder'.date('YmdHis', time()), 1)];
    }

    /**
     * 编辑订单
     * @param PhysicalReplaceOrderForm $physicalReplaceOrderForm
     * @return bool
     * @throws \yii\db\Exception
     * @throws Exception
     * @author weifeng
     */

    public function update(PhysicalReplaceOrderForm $physicalReplaceOrderForm): bool
    {
        //更新实体
        $res = $this->physicalReplaceOrderEntity->updateEntity($physicalReplaceOrderForm);
        if (!$res) {
            throw new Exception('编辑失败！请重试！！！');
        }
        return $res;
    }

    /**
     * 删除订单
     * @param PhysicalReplaceOrderDto $physicalReplaceOrderDto
     * @return int|mixed
     * @throws Exception
     * @author weifeng
     */
    public function delete(PhysicalReplaceOrderDto $physicalReplaceOrderDto)
    {
        $res = $this->physicalReplaceOrderEntity->deleteEntity($physicalReplaceOrderDto);
        if (!$res) {
            throw new Exception('删除失败！请重试！！！');
        }
        return $res;
    }

    /**
     * 审核订单（初审、终审）
     * @param PhysicalReplaceOrderDto $physicalReplaceOrderDto
     * @return array|bool
     * @throws Exception
     * @author weifeng
     */
    public function audit(PhysicalReplaceOrderDto $physicalReplaceOrderDto)
    {
        $record = $this->physicalReplaceOrderDoManager->findOne($physicalReplaceOrderDto->id);
        if (!$record) {
            throw new Exception('获取数据失败！请重试！！！');
        }
        //接收字段信息
        $arrayData = $record->attributes;
        //初审状态
        if (!empty($physicalReplaceOrderDto->first_trial)) {
            if (empty($arrayData['final_judgment'])) {
                $res = $this->physicalReplaceOrderEntity->auditEntity($physicalReplaceOrderDto);
                if (!$res) {
                    throw new Exception('初审审核失败！请重试！！！');
                }
                $firstCord = $this->physicalReplaceOrderDoManager->findOne($physicalReplaceOrderDto->id);
                if (!$firstCord) {
                    throw new Exception('获取数据失败！请重试！！！');
                }
                //接收字段信息
                $fRes = $firstCord->attributes;
                return ['first_trial' => $fRes['first_trial'], 'first_audit_opinion' => $fRes['first_audit_opinion'], 'first_auditor' => $fRes['first_auditor']];
            }
            throw new Exception('初审失败，终审已审核！');
        }
        //终审状态
        if (!empty($physicalReplaceOrderDto->final_judgment)) {
            if (!empty($arrayData['first_trial'])) {
                if ($arrayData['first_trial'] === 1) {
                    $res = $this->physicalReplaceOrderEntity->auditEntity($physicalReplaceOrderDto);
                    if (!$res) {
                        throw new Exception('终审审核失败！请重试！！！');
                    }
                    $cord = $this->physicalReplaceOrderDoManager->findOne($physicalReplaceOrderDto->id);
                    if (!$cord) {
                        throw new Exception('获取数据失败！请重试！！！');
                    }
                    //接收字段信息
                    $dRes = $cord->attributes;
                    return ['final_judgment' => $dRes['final_judgment'], 'final_audit_opinion' => $dRes['final_audit_opinion'], 'final_auditor' => $dRes['final_auditor']];
                }
                throw new Exception('终审失败，初审为待审核或不通过！');
            }
            throw new Exception('终审失败，初审未审核！');
        }
        return false;
    }

    /**
     * 更新订单
     * @param PhysicalReplaceOrderImport $physicalReplaceOrderImport
     * @return int|mixed
     * @throws Exception
     * @author weifeng
     */

    public function updateReplaceOrder(PhysicalReplaceOrderImport $physicalReplaceOrderImport)
    {
        $physicalReplaceOrderImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        if ($physicalReplaceOrderImport->excelFile === null) {
            throw new Exception('excel上传文件不能为空');
        }
        $data = ExcelFacade::import($physicalReplaceOrderImport->excelFile->tempName);
        $data = $this->dealUpdateData($data);
        //不需要的字段
        $ids = $this->extractExportId($data);
        //根据id更新
        $columnValue = ['we_chat_id','advert_location','dispatch_time','put_link','advert_read_num','volume_transaction','new_fan_attention'];
        $sql = $this->physicalReplaceOrderDoManager->getBatchUpdateSql($this->model::tableName(), $columnValue, array_values($data), $ids, 'id');
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * 更新寄出状态
     * @param PhysicalReplaceOrderImport $physicalReplaceOrderImport
     * @return int
     * @throws Exception
     */

    public function updatePrizeSendStatus(PhysicalReplaceOrderImport $physicalReplaceOrderImport)
    {
        $physicalReplaceOrderImport->excelFile = UploadedFile::getInstanceByName('excelFile');
        if ($physicalReplaceOrderImport->excelFile === null) {
            throw new RuntimeException('excel上传文件不能为空');
        }
        $data = ExcelFacade::import($physicalReplaceOrderImport->excelFile->tempName,0,0,true);
        //检测表头是否合法
        $headArr = ['A' => '微信号', 'B' => '发文时间', 'C' => '广告位置',
                    'D' => '收件人', 'E' => '联系电话', 'F' => '收件地址', 'G' => '快递单号'];
        $diff = array_diff(array_shift($data), $headArr);
        if (!empty($diff)) {
            /** @noinspection LoopWhichDoesNotLoopInspection */
            foreach ($diff as $i) {
                throw new RuntimeException('请检查表头‘' . $i . '’是否正确');
            }
        }
        $data = $this->dealStatusData($data);
        if (!empty(array_unique(end($data))) && !empty(array_unique(end($data))[0])) {
            $this->model::updateAll(['prize_send_status' => 1], ['in', 'id', array_unique(end($data))]);
            return Yii::$app->db
                            ->createCommand()
                            ->batchInsert($this->physicalSendStatusDo::tableName(), array_diff($this->physicalSendStatusDo->attributes(), ['id']), array_shift($data))
                            ->execute();
        }
        throw new RuntimeException('微信号、发文时间不匹配该记录，请重试！！！');
    }


    /**
     * 处理导入数据
     * @param array $data
     * @return array
     */
    private function dealImportData(array $data):array
    {
        //检查微信号 昵称 发文时间是否为空
        if (empty($data)) {
            throw new RuntimeException('导入数据为空');
        }
        $num = [];
        $dispatch = [];
        foreach ($data as $k => $v) {
            if (empty($v['A']) || empty($v['B']) || empty($v['F'])) {
                $num[] = $k;
            }
            if (!empty($v['F'])) {//不可重复操作
                $data[$k]['F'] = strtotime($v['F']);
                $dispatch[] = $data[$k]['B'] .$data[$k]['D'] . $data[$k]['F'];
                if (!empty($dispatch)) {
                    $res = $this->model::find()
                        ->where(['we_chat_id' => $data[$k]['B'], 'advert_location' => $data[$k]['D'], 'dispatch_time' => $data[$k]['F']])
                        ->asArray()
                        ->one();
                    if ($res) {
                        throw new RuntimeException('第' . ($k + 1) . '行微信号、广告位置、发文时间有重复数据，请检查表格是否正确！！！');
                    }
                    unset($data[$k]['O'], $data[$k]['P'], $data[$k]['Q']);
                }
            }
        }
        //检查重复记录
        if (count($dispatch) !== count(array_unique($dispatch))) {
            throw new RuntimeException('微信号、广告位置、发文时间有重复数据，请检查表格是否正确！！！');
        }
        if (!empty($num)) {
            throw new RuntimeException('第' . implode(',', $num) . '行记录的微信号、昵称、广告位置、发文时间不能为空');
        }
        return $data;
    }

    /**
     * 处理更新订单数据
     * @param $data
     * @return mixed
     * @throws Exception
     * @author weifeng
     */

    private function dealUpdateData($data)
    {
        //检查微信号 昵称 发文时间是否为空
        if (empty($data)) {
            throw new RuntimeException('导入数据为空');
        }
        $num = [];
        $dispatch = [];
        foreach ($data as $k => $v) {
            if (empty($v['A']) || empty($v['B']) || empty($v['C'])) {
                $num[] = $k;
            }
            if (!empty($v['C'])) {//不可重复操作
                $data[$k]['C'] = strtotime($v['C']);
                $dispatch[] = $data[$k]['A'] .$data[$k]['B'] . $data[$k]['C'];
            }
        }
        //检查重复记录
        if (count($dispatch) !== count(array_unique($dispatch))) {
            throw new RuntimeException('微信号、广告位置、发文时间有重复数据，请检查表格是否正确！！！');
        }
        if (!empty($num)) {
            throw new RuntimeException('第' . implode(',', $num) . '行记录的微信号、昵称、广告位置、发文时间不能为空');
        }
        return $data;
    }

    /**
     * 获取微信号和发文时间对应的id
     * @param $data
     * @return array
     * @throws Exception
     */
    private function extractExportId($data)
    {
        $ids = [];
        foreach ($data as $key => $value) {
            $id = $this->model::find()
                ->select('id')
                ->where(['we_chat_id' => $value['A'], 'advert_location' => $value['B'], 'dispatch_time' => $value['C']])
                ->asArray()
                ->one();
            if (empty($id)) {
                throw new Exception('第'.($key + 1) . '行的数据不匹配，更新失败');
            }
            $ids[] = array_shift($id);
        }
        $idArr = array_unique($ids);
        if (count($idArr) !== count($ids)) {
            throw new Exception('有重复数据，更新失败');
        }
        return $ids;
    }

    /**
     * 处理导出的列表数据
     * @param $listData
     * @return mixed
     */
    private function dealExportListData($listData)
    {
        if (!empty($listData)) {
            foreach ($listData as $key => $data) {
                if (!empty($data['dispatch_time'])) {
                    $listData[$key]['dispatch_time'] = date('Y-m-d H:i:s', intval($data['dispatch_time']));
                }
                switch ($data['first_trial']) {
                    case '0' :
                        $listData[$key]['first_trial'] = ' 待审核';
                        break;
                    case '1' :
                        $listData[$key]['first_trial'] = ' 已通过';
                        break;
                    case '2':
                        $listData[$key]['first_trial'] = ' 不通过';
                        break;
                }
                switch ($data['final_judgment']) {
                    case '0' :
                        $listData[$key]['final_judgment'] = ' 待审核';
                        break;
                    case '1' :
                        $listData[$key]['final_judgment'] = ' 已通过';
                        break;
                    case '2':
                        $listData[$key]['final_judgment'] = ' 不通过';
                        break;
                }
                if ($data['prize_send_status'] == '0') {
                    $listData[$key]['prize_send_status'] = '未发货';
                } else {
                    $listData[$key]['prize_send_status'] = '已发货';
                }
                //筛选不需要导出的字段
                unset($listData[$key]['id'], $listData[$key]['audit_opinion'], $listData[$key]['first_audit_opinion'],
                    $listData[$key]['final_audit_opinion'], $listData[$key]['first_auditor'], $listData[$key]['final_auditor']);
            }
            return $listData;
        }
        return $listData;
    }

    /**
     * 处理导入寄出状态数据
     * @param $data
     * @return array
     * @throws Exception
     */
    private function dealStatusData($data):array
    {
        //检查微信号  发文时间  广告位置 是否为空
        if (empty($data)) {
            throw new RuntimeException('导入数据为空！！！');
        }
        $num            = [];
        $trackingNumber = [];
        $rpId           = [];
        foreach ($data as $k => $v) {
            if (empty($v['A']) || empty($v['B']) || empty($v['C']) || empty($v['G'])) {
                $num[] = $k;
            }
            $data[$k]['B'] = strtotime($v['B']);
            $trackingNumber[] = $v['G'];
            $tN = $this->physicalSendStatusDo::find()->where(['tracking_number' => $v['G']])->one();
            if ($tN){
                throw new RuntimeException('第'.($k+1).'行的快递单号已存在，请检查再导入！！！');
            }
        }
        if (!empty($num)) {
            throw new RuntimeException('第' . implode(',', $num) . '条记录的微信号、发文时间、广告位置、快递单号不能为空！！！');
        }
        if (count($trackingNumber) !== count(array_unique($trackingNumber))){
            throw new RuntimeException('快递单号的内容重复，请检查再导入！！！');
        }
        foreach ($data as $key => $d) {
            $id = $this->model::find()
                ->select('id,final_judgment')
                ->where(['we_chat_id' => $d['A'], 'dispatch_time' => $d['B'], 'advert_location' => $d['C']])
                ->asArray()
                ->one();
            if (empty($id)){
                throw new RuntimeException('没有匹配到订单，更新寄出状态失败');
            }
            if (empty($id['final_judgment'])){
                throw new RuntimeException('终审未通过，更新寄出状态失败');
            }
            $rpId[] = $data[$key]['rp_id'] = $id['id'];
            unset($data[$key]['A'], $data[$key]['B'], $data[$key]['C']);
        }
        return [$data,$rpId];
    }

    /**
     * 数据统计
     * @param $data
     * @return array
     */

    private function statisticsData($data)
    {
        $replaceQuantity = $advertReadNum = $volumeTransaction = $newFanAttention = $weChatId = 0;
        foreach ($data as &$v) {
            if (!empty($v['replace_quantity'])) {
                $replaceQuantity += (int)$v['replace_quantity'];
            }
            if (!empty($v['advert_read_num'])) {
                $advertReadNum += (int)$v['advert_read_num'];
            }
            if (!empty($v['volume_transaction'])) {
                $volumeTransaction += (int)$v['volume_transaction'];
            }
            if (!empty($v['new_fan_attention'])) {
                $newFanAttention += (int)$v['new_fan_attention'];
            }
            if (!empty($v['we_chat_id'])) {
                ++$weChatId;
            }
        }
        return
            [
                'replaceQuantity'   => $replaceQuantity,
                'advertReadNum'     => $advertReadNum,
                'volumeTransaction' => $volumeTransaction,
                'newFanAttention'   => $newFanAttention,
                'weChatId'          => $weChatId
            ];
    }

    /**
     * 获取品牌数组，前端需要品牌遍历
     */
    private function getBrandArr(){
        $brandArr = $this->model::find()->select('brand')->distinct()->asArray()->all();
        $brand = [];
        foreach ($brandArr as $k =>$b){
            if (!empty($b['brand'])){
                $brand[$k] = $b['brand'];
            }
        }
        return $brand;
    }

}