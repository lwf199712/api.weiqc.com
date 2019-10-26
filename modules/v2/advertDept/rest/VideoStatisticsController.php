<?php
declare(strict_types=1);

namespace app\modules\v2\advertDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\advertDept\domain\aggregate\VideoStatisticsAggregate;
use app\modules\v2\advertDept\domain\dto\VideoStatisticsDto;
use Exception;
use RuntimeException;
use yii\base\Model;

class VideoStatisticsController extends AdminBaseController
{
    /** @var VideoStatisticsDto */
    private $videoStatisticsDto;

    /** @var VideoStatisticsAggregate */
    private $videoStatisticsAggregate;

    public function __construct($id, $module,
                                VideoStatisticsAggregate $videoStatisticsAggregate,
                                VideoStatisticsDto $videoStatisticsDto,
                                $config = [])
    {
        $this->videoStatisticsAggregate = $videoStatisticsAggregate;
        $this->videoStatisticsDto = $videoStatisticsDto;
        parent::__construct($id, $module, $config);
    }

    protected function verbs(): array
    {
        return [
            'index'                 => ['GET', 'HEAD', 'OPTIONS'],
            'videoTestDetail'       => ['GET', 'HEAD', 'OPTIONS'],
            'exportVideoStatistics' => ['GET', 'HEAD', 'OPTIONS'],
            'exportVideoTestDetail' => ['GET', 'HEAD', 'OPTIONS']
        ];
    }

    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
            case 'actionExportVideoStatistics':
                return $this->videoStatisticsDto;
                break;
            case 'actionVideoTestDetail':
            case 'actionExportVideoTestDetail':
                $this->videoStatisticsDto->setScenario(VideoStatisticsDto::VIDEO_TEST_DETAIL_INDEX);
                return $this->videoStatisticsDto;
                break;
            default:
                throw new RuntimeException('unKnow actionName', 500);
        }
    }

    /**
     * 视频统计首页
     * @return array
     * @author dengkai
     * @date   2019/10/7
     */
    public function actionIndex(): array
    {
        $data = $this->videoStatisticsAggregate->getVideoStatisticsHomePageData($this->videoStatisticsDto);
        return ['success', 200, $data];
    }

    /**
     * 视频测试详情首页
     * @return array
     * @author dengkai
     * @date   2019/10/7
     */
    public function actionVideoTestDetail(): array
    {
        $data = $this->videoStatisticsAggregate->getVideoTestDetailHomePageData($this->videoStatisticsDto);
        return ['success', 200, $data];
    }

    /**
     * 导出视频统计首页数据
     * @return array
     * @throws Exception
     * @author dengkai
     * @date   2019/10/7
     */
    public function actionExportVideoStatistics(): array
    {
        $data = $this->videoStatisticsAggregate->exportVideoStatisticData($this->videoStatisticsDto);
        return ['success', 200, $data];
    }

    /**
     * 导出视频测试详情首页数据
     * @return array
     * @author dengkai
     * @date   2019/10/7
     */
    public function actionExportVideoTestDetail(): array
    {
        $data = $this->videoStatisticsAggregate->exportVideoTestDetailData($this->videoStatisticsDto);
        return ['success', 200, $data];
    }
}