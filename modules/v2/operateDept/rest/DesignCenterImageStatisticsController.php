<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageStatisticsDto;
use app\modules\v2\operateDept\service\DesignCenterImageStatisticsService;
use Exception;
use http\Exception\RuntimeException;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class DesignCenterImageStatisticsController
 * @package app\modules\v2\operateDept\rest
 */
class DesignCenterImageStatisticsController extends AdminBaseController
{
    /** @var DesignCenterImageStatisticsService */
    public $designCenterImageStatisticsService;
    /** @var DesignCenterImageStatisticsDto */
    public $designCenterImageStatisticsDto;

    public function __construct($id, $module,
                                DesignCenterImageStatisticsService $designCenterImageStatisticsService,
                                DesignCenterImageStatisticsDto     $designCenterImageStatisticsDto,
                                $config = [])
    {
        $this->designCenterImageStatisticsService = $designCenterImageStatisticsService;
        $this->designCenterImageStatisticsDto     = $designCenterImageStatisticsDto;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index'      => ['GET', 'HEAD', 'OPTIONS'],
            'statistics' => ['GET', 'HEAD', 'OPTIONS'],
            'personal' => ['GET', 'HEAD', 'OPTIONS'],
        ];
    }

    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @throws Exception
     * @author: weifeng
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->designCenterImageStatisticsDto;
            case 'actionStatistics':
                return $this->designCenterImageStatisticsDto;
            case 'actionPersonal':
                return $this->designCenterImageStatisticsDto;
            default:
                throw new HttpException('UnKnow ActionName ');
        }
    }

    /**
     * 设计中心图片统计-首页
     * @return array
     * @author: weifeng
     */
    public function actionIndex(): array
    {
        try {
            $data = $this->designCenterImageStatisticsService->listImage($this->designCenterImageStatisticsDto);
            return ['成功返回数据', 200, $data];
        } catch (Exception $exception) {
            return ['查询失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心图片统计-审核统计
     * @return array
     * @author: weifeng
     */
    public function actionStatistics(): array
    {
        try {
            $data = $this->designCenterImageStatisticsService->auditStatistics($this->designCenterImageStatisticsDto);
            return ['成功返回数据', 200, $data];
        } catch (Exception $exception) {
            return ['查询失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 设计中心图片统计-个人图片统计
     * Date: 2019/11/18
     * Author: ctl
     */
    public function actionPersonal():array
    {
        try{
            if (!$this->designCenterImageStatisticsDto->stylist)
            {
                throw new RuntimeException('设计师名称不能为空');
            }
            $data = $this->designCenterImageStatisticsService->personalStatistics($this->designCenterImageStatisticsDto);
            return ['成功返回数据', 200, $data];
        }catch (Exception $exception){
            return ['查询失败', 500, $exception->getMessage()];
        }
    }
}