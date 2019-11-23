<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\service\impl;

use app\modules\v2\operateDept\domain\dto\DesignCenterImageStatisticsDto;
use app\modules\v2\operateDept\domain\repository\DesignCenterImageStatisticsDoManager;
use app\modules\v2\operateDept\service\DesignCenterImageStatisticsService;
use Exception;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

class DesignCenterImageStatisticsImpl extends BaseObject implements DesignCenterImageStatisticsService
{
    /** @var DesignCenterImageStatisticsDoManager */
    public $designCenterImageStatisticsDoManager;
    /** @var DesignCenterImageStatisticsDto */
    public $designCenterImageStatisticsDto;
    /** @var ActiveRecord */
    public $model;


    public function __construct(
        /** @noinspection InterfacesAsConstructorDependenciesInspection */
        DesignCenterImageStatisticsDoManager $designCenterImageStatisticsDoManager,
        DesignCenterImageStatisticsDto       $designCenterImageStatisticsDto,
                                             $config = [])
    {
        $this->designCenterImageStatisticsDoManager = $designCenterImageStatisticsDoManager;
        $this->designCenterImageStatisticsDto       = $designCenterImageStatisticsDto;
        parent::__construct($config);
    }

    /**
     * 设计中心图片统计-列表
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return array
     * @author: weifeng
     */
    public function listImage(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): array
    {
        $list['lists'] = $this->designCenterImageStatisticsDoManager->listDataProvider($designCenterImageStatisticsDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['drawCount'] = $value['homePage'] + $value['mainImage'] + $value['productDetail'] + $value['drillShow'] + $value['throughCar'] + $value['landingPage']+$value['tweet']+$value['describe790']+$value['storeActivity']+$value['slideShow']+$value['videoMainImage']+$value['truingScene'];
            unset($list['lists'][$key]['id']);
        }
        $list['totalCount'] = $this->designCenterImageStatisticsDoManager->listDataProvider($designCenterImageStatisticsDto)->getTotalCount();
        return $list;
    }

    /**
     * 设计中心图片统计-审核统计
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return array
     * @author: weifeng
     */
    public function auditStatistics(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): array
    {
        $dataArr = $this->designCenterImageStatisticsDoManager->auditStatistics($designCenterImageStatisticsDto)->getModels();
        $listArr = [];
        if (!empty($dataArr)){
            $listArr = array_shift($dataArr);
            unset($listArr['id']);
        }
        return $listArr;
    }

    /**
     * 设计中心设计师个人图片统计
     * Date: 2019/11/17
     * Author: ctl
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return array
     * @throws Exception
     */
    public function personalStatistics(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): array
    {
        $dataArr = $this->designCenterImageStatisticsDoManager->personalStatistics($designCenterImageStatisticsDto)->getModels();
        return $dataArr;
        // TODO: Implement personalStatistics() method.
    }
}