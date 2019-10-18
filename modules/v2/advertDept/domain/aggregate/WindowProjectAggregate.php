<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/14
 * Time: 14:30
 */

namespace app\modules\v2\advertDept\domain\aggregate;


use app\common\facade\ExcelFacade;
use app\modules\v2\advertDept\domain\dto\WindowProjectDto;
use app\modules\v2\advertDept\domain\dto\WindowProjectForm;
use app\modules\v2\advertDept\domain\entity\ProductLibraryEntiy;
use app\modules\v2\advertDept\domain\repository\WindowProjectDoManager;
use yii\base\BaseObject;
use app\modules\v2\advertDept\domain\entity\WindowProjectEntiy as WindowProjectAggregateRoot;
use yii\db\Exception;

/**
 * Class WindowProjectAggregate
 * @property WindowProjectAggregateRoot $windowProjectAggregateRoot
 * @property WindowProjectDto $windowProjectDto
 * @property WindowProjectDoManager $windowProjectDoManager
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class WindowProjectAggregate extends BaseObject
{
    /** @var WindowProjectAggregateRoot */
    private $windowProjectAggregateRoot;
    /** @var WindowProjectDto */
    private $windowProjectDto;
    /** @var WindowProjectDoManager */
    private $windowProjectDoManager;

    public function __construct(
        WindowProjectAggregateRoot $windowProjectAggregateRoot,
        WindowProjectDto $windowProjectDto,
        WindowProjectDoManager $windowProjectDoManager,
        $config = [])
    {
        $this->windowProjectAggregateRoot = $windowProjectAggregateRoot;
        $this->windowProjectDto = $windowProjectDto;
        $this->windowProjectDoManager = $windowProjectDoManager;
        parent::__construct($config);
    }

    /**
     * @param WindowProjectForm $windowProjectForm
     * @return bool
     * @throws Exception
     * @throws \Exception
     * author: pengguochao
     * Date Time 2019/10/16 10:04
     */
    public function createWindowProject(WindowProjectForm $windowProjectForm): bool
    {
        $productLibraryEntiy = new ProductLibraryEntiy();
        $productLibraryInfo = $productLibraryEntiy->findOneEntiy($windowProjectForm->product_name);
        if (!$productLibraryInfo){
            throw new Exception('找不到改产品名称，不能新增橱窗项目，请核对好产品名称再新增');
        }
        $result = $this->windowProjectAggregateRoot->createEntity($windowProjectForm);
        if ($result === false) {
            throw new Exception('新增橱窗项目失败');
        }
        return $result;
    }

    public function listWindowProject(WindowProjectDto $windowProjectDto)
    {
        if (isset($windowProjectDto->toArray(['beginTime'])['beginTime'])){
            $windowProjectDto->setAttributes(['beginTime' => strtotime($windowProjectDto->getAttributes(['beginTime'])['beginTime'])]);
        }
        if (isset($windowProjectDto->toArray(['endTime'])['endTime'])){
            $windowProjectDto->setAttributes(['endTime' => strtotime($windowProjectDto->getAttributes(['endTime'])['endTime'])]);
        }
        $data = $this->windowProjectDoManager->listDataProvider($windowProjectDto, ['id' => SORT_DESC])->getModels();
        $i = 0;
        array_map(static function ($value) use (&$i,&$data){
            $data[$i]['data_time'] = date('Y-m-d',(int)$value['data_time']);
            $data[$i]['create_at'] = date('Y-m-d H:i:s',(int)$value['create_at']);
            $data[$i]['period'] = $value['period'] . '点～' .($value['period']+1) . '点';
            $i++;
        },$data);
        $list['lists'] = $data;
        $list['totalCount'] = $this->windowProjectDoManager->listDataProvider($windowProjectDto, ['id' => SORT_DESC])->getTotalCount();
        return $list;
    }

    /**
     * 获取橱窗项目实体详情
     * @param int $id
     * @return array
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/17 9:08
     */
    public function detailWindowProject(int $id): array
    {
        $detailWindowProject = $this->windowProjectAggregateRoot->detailEntity($id);
        if (!$detailWindowProject){
            throw new Exception('查看详情失败，或者找不到该橱窗项目信息');
        }
        return $detailWindowProject->attributes;
    }

    /**
     * 更新橱窗项目
     * @param WindowProjectForm $windowProjectForm
     * @return bool
     * @throws Exception
     * @throws \Exception
     * author: pengguochao
     * Date Time 2019/10/17 12:24
     */
    public function updateWindowProject(WindowProjectForm $windowProjectForm): bool
    {
        $productLibraryEntiy = new ProductLibraryEntiy();
        $productLibraryInfo = $productLibraryEntiy->findOneEntiy($windowProjectForm->product_name);
        if (!$productLibraryInfo){
            throw new Exception('找不到改产品名称，不能更新，请核对好产品名称再更新');
        }
        $windowProjectForm->setAttributes(['data_time' => strtotime($windowProjectForm->getAttributes(['data_time'])['data_time'])]);
        $result = $this->windowProjectAggregateRoot->updateEntity($windowProjectForm);
        if (!$result){
            throw new Exception('更新橱窗项目失败');
        }
        return true;
    }

    /**
     * 删除橱窗项目
     * @param WindowProjectDto $windowProjectDto
     * @return int
     * author: pengguochao
     * Date Time 2019/10/17 13:36
     */
    public function deleteWindowProject(WindowProjectDto $windowProjectDto): int
    {
        return $this->windowProjectAggregateRoot->deleteEntity($windowProjectDto);
    }

    /**
     * 查询导出数据
     * @param WindowProjectDto $windowProjectDto
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/17 18:12
     */
    public function exportWindowProject(WindowProjectDto $windowProjectDto): void
    {
        $windowProjectDto->setAttributes(['data_time' => strtotime($windowProjectDto->getAttributes(['data_time'])['data_time'])]);
        $dateTime = date('md', $windowProjectDto->getAttributes(['data_time'])['data_time']);
        $data = $this->windowProjectDoManager->exportData($windowProjectDto);
        if (!$data){
            throw new \RuntimeException('查到的橱窗项目的数据为空');
        }
        $productLibraryEntiy = new ProductLibraryEntiy();
        $productLibraryInfo = $productLibraryEntiy->findOneEntiy($windowProjectDto->product_name);
        if (!$productLibraryInfo){
            throw new Exception('找不到改产品名称，不能更新，请核对好产品名称再更新');
        }
        $i = 0;
        $commissionRate = $productLibraryInfo->commission_rate;     //抽佣率
        array_map(static function ($value) use(&$i, &$data, &$commissionRate){
            if (strlen($value['period']) === 1){
                $data[$i]['period'] = '0' . $value['period'] . ':00-' . '0' . $value['period'] . ':59';
            }else{
                $data[$i]['period'] = $value['period'] . ':00-' . $value['period'] . ':59';
            }
            $data[$i]['unnecessaryTurnover'] = $value['total_turnover'] - $value['real_turnover'];  //余效成交（总成交-实时成交）
            $commission = ($value['total_turnover']*$commissionRate)/100;                           //抽佣（总成交*抽佣率）
            $data[$i]['commission'] = $commission;                                                  //抽佣（总成交*抽佣率）
            $data[$i]['realTimeTransaction'] = $value['real_turnover'] - $commission;               //实时真实成交（实时成交-抽佣）
            $data[$i]['trueTransaction'] = $value['total_turnover'] -$commission;                   //真实成交（总成交-抽佣）
            $data[$i]['realTimeROI'] = $data[$i]['realTimeTransaction']/$value['consume'];          //实时ROI（实时真实成交/消耗）
            $data[$i]['totalROI'] = $data[$i]['trueTransaction']/$value['consume'];                 //总ROI（真实成交/消耗）
            $i++;
        },$data);
        $tableHeader = [$dateTime . '-MVE抖加每小时数据投放汇总（' . $windowProjectDto->account_and_id . '--成交来自' . $windowProjectDto->delivery_platform . '）'];
        $tableHeaderTwo = [
            'period' => '时间', 'real_turnover' => '实时成交', 'total_turnover' => '总成交', 'consume' => '总消耗', 'transaction_data' => $dateTime . '生意参谋成交数据',
            'unnecessaryTurnover' => '余效成交（总成交-实时成交）', 'commission' => '抽佣（总成交*抽佣率）', 'realTimeTransaction' => '实时真实成交（实时成交-抽佣）',
            'trueTransaction' => '真实成交（总成交-抽佣）', 'realTimeROI' => '实时ROI（实时真实成交/消耗）', 'totalROI' => '总ROI（真实成交/消耗）'
        ];
        /*$tableHeader = [
            'period' => '时间', 'unnecessaryTurnover' => '余效成交（总成交-实时成交）', 'real_turnover' => '实时成交',
            'total_turnover' => '总成交', 'commission' => '抽佣（总成交*抽佣率）', 'realTimeTransaction' => '实时真实成交（实时成交-抽佣）',
            'trueTransaction' => '真实成交（总成交-抽佣）', 'consume' => '总消耗', 'realTimeROI' => '实时ROI（实时真实成交/消耗）',
            'totalROI' => '总ROI（真实成交/消耗）', 'transaction_data' => $dateTime . '生意参谋成交数据'
        ];*/
        ExcelFacade::export(array_merge([$tableHeader], [$tableHeaderTwo], $data), '-MVE2', 1);
    }
}