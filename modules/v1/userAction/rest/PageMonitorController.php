<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\rest;


use app\common\rest\RestBaseController;
use app\common\utils\UrlUtils;
use app\modules\v1\userAction\domain\dto\PageMonitorRequestDto;
use app\modules\v1\userAction\domain\vo\PageMonitorModuleVo;
use app\modules\v1\userAction\domain\vo\PageMonitorPageVo;
use app\modules\v1\userAction\service\UserActionPageMonitorService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use Exception;
use Yii;
use yii\base\InvalidConfigException;

/**
 * @property UserActionStaticUrlService   $userActionStaticUrlService
 * @property UserActionPageMonitorService $userActionPageMonitorService
 * @property UrlUtils                     $urlUtils,
 * Class PageMonitorController
 * @package app\modules\v1\userAction\rest
 */
class PageMonitorController extends RestBaseController
{
    /** @var UserActionStaticUrlService */
    public $userActionStaticUrlService;
    /** @var  UserActionPageMonitorService */
    public $userActionPageMonitorService;
    /** @var UrlUtils */
    protected $urlUtils;

    public function __construct($id, $module,
                                UserActionStaticUrlService $userActionStaticUrlService,
                                UserActionPageMonitorService $userActionPageMonitorService,
                                UrlUtils $urlUtils,
                                $config = [])
    {
        $this->userActionStaticUrlService   = $userActionStaticUrlService;
        $this->userActionPageMonitorService = $userActionPageMonitorService;
        $this->urlUtils                     = $urlUtils;
        parent::__construct($id, $module, $config);
    }

    /**
     * Declares the allowed HTTP verbs.
     *
     * @return array
     * @author: lirong
     */
    public function verbs(): array
    {
        return [
            'post-data' => ['POST', 'HEAD'],
        ];
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @author zhuozhen
     */
    public function actionPostData(): array
    {
        $origin                = $this->request->getOrigin();
        $host                  = $this->request->getHostName();
        $pageMonitorRequestDto = new PageMonitorRequestDto($this->request->post());
        if (in_array($host, Yii::$app->params['params']['cross_domain'], false)) {
            header('Access-Control-Allow-Origin:' . $origin);
        }
        if (empty($pageMonitorRequestDto->token)) {
            return [$origin . $host . '无法获取到token！', 406];
        }
        $staticUrl = $this->userActionStaticUrlService->findOne(['ident' => $pageMonitorRequestDto->token]);
        if ($staticUrl === null) {
            return [$origin . $host . 'token值验证失败！', 406];
        }

        $url             = $this->request->getReferrer() ?? $this->urlUtils->getClientUrl($this->request);
        $ipAddress       = $this->request->getUserIP();
        $ip              = ip2long($ipAddress);
        $currentPageList = explode(',', $pageMonitorRequestDto->current_page);
        $durationList    = explode(',', $pageMonitorRequestDto->duration);

        try {
            $insertData = [];
            foreach ($currentPageList as $key => $currentPage) {
                $pageMonitorPageVo = new PageMonitorPageVo();
                $pageMonitorPageVo->setAttributes($this->request->post());
                $pageMonitorPageVo->current_page   = $currentPage;
                $pageMonitorPageVo->duration       = $durationList[$key];
                $pageMonitorPageVo->total_duration = array_sum($durationList);
                $pageMonitorPageVo->url_id         = $staticUrl->id;
                $pageMonitorPageVo->ip             = $ip;
                $pageMonitorPageVo->jumpout_url    = $url;
                $pageMonitorPageVo->create_time    = time();
                $insertData[]                      = $pageMonitorPageVo;
            }
            $effectRow = $this->userActionPageMonitorService->batchInsertPageData($insertData);


            $currentModuleList  = explode(',', $pageMonitorRequestDto->current_module);
            $moduleDurationList = explode(',', $pageMonitorRequestDto->module_duration);

            //如果模块为空，证明是H5的多页面形式，则不插入模块记录
            //模块不为空，证明是有多模块的长图文形式（页面只有一页）.插入模块记录
            $insertData = [];
            if (!empty($pageMonitorRequestDto->current_module) && $effectRow === 1) {
                foreach ($currentModuleList as $key => $currentModule) {
                    $pageMonitorModuleVo                 = new PageMonitorModuleVo();
                    $pageMonitorModuleVo->url_id         = $staticUrl->id;
                    $pageMonitorModuleVo->page_id        = Yii::$app->db->getLastInsertID();
                    $pageMonitorModuleVo->current_module = $currentModule;
                    $pageMonitorModuleVo->duration       = $moduleDurationList[$key];
                    $pageMonitorModuleVo->ip             = $ip;
                    $pageMonitorModuleVo->create_time    = time();
                    $insertData[]                        = $pageMonitorModuleVo;
                }
                $this->userActionPageMonitorService->batchInsertPageModuleData($insertData);
            }
            return ['统计成功', 200];

        }catch (Exception $exception){
            Yii::info($exception->getTrace());
            return ['失敗！！！'. $exception->getTrace(), 500];
        }
    }
}