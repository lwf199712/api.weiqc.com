<?php

namespace app\modules\v1\conversion\rest;

use app\api\tencentMarketingApi\userActions\api\UserActionsAip;
use app\common\rest\RestBaseController;
use app\common\exception\TencentMarketingApiException;
use app\common\exception\ValidateException;
use app\modules\v1\conversion\domain\po\StaticConversionPo;
use app\modules\v1\conversion\domain\po\StaticUrl;
use app\modules\v1\conversion\domain\vo\ConversionInfo;
use app\modules\v1\conversion\service\impl\StaticConversionImpl;
use app\modules\v1\conversion\service\impl\StaticServiceConversionsImpl;
use app\modules\v1\conversion\service\impl\StaticUrlImpl;
use app\modules\v1\conversion\service\StaticConversionService;
use app\modules\v1\conversion\service\StaticServiceConversionsService;
use app\modules\v1\conversion\service\StaticUrlService;
use app\utils\IpLocationUtils;
use app\utils\ResponseUtils;
use app\utils\SourceDetectionUtil;
use app\utils\RequestUtils;
use Exception;

/**
 * Landing page conversions（copy WeChat）.
 * Class ConversionController
 *
 * @property SourceDetectionUtil $sourceDetectionUtil
 * @property IpLocationUtils $ipLocationUtils
 * @property RequestUtils $requestUtils
 * @property ConversionInfo $conversionInfo
 * @property StaticUrlService $staticUrlService
 * @property StaticConversionService $staticConversion
 * @property StaticServiceConversionsImpl $staticServiceConversionsService
 * @property UserActionsAip $userActionsController
 * @package app\modules\v1\rest
 * @author: lirong
 */
class RestController extends RestBaseController
{
    /* @var ResponseUtils */
    public $responseUtils = ResponseUtils::class;
    /* @var StaticUrl */
    public $modelClass = StaticUrl::class;
    /* @var SourceDetectionUtil */
    private $sourceDetectionUtil = SourceDetectionUtil::class;
    /* @var IpLocationUtils */
    private $ipLocationUtils = IpLocationUtils::class;
    /* @var RequestUtils */
    private $requestUtils = RequestUtils::class;
    /* @var ConversionInfo */
    private $conversionInfo = ConversionInfo::class;
    /* @var StaticUrlService */
    private $staticUrlService = StaticUrlImpl::class;
    /* @var StaticConversionService */
    private $staticConversion = StaticConversionImpl::class;
    /* @var StaticServiceConversionsService */
    private $staticServiceConversionsService = StaticServiceConversionsImpl::class;
    /* @var UserActionsAip */
    private $userActionsController = UserActionsAip::class;

    /**
     * Declares the allowed HTTP verbs.
     *
     * @return array
     * @author: lirong
     */
    public function verbs(): array
    {
        return ['add-conversion' => ['POST', 'HEAD']];
    }

    /**
     * Landing page conversions - add conversions
     *
     * @author: lirong
     */
    public function actionAddConversion(): array
    {
        try {
            /* @var $conversionInfo ConversionInfo */
            $conversionInfo = new $this->conversionInfo;
            $conversionInfo->setAttributes($this->request->post());
            $this->sourceDetectionUtil::crossDomainDetection();
            $staticUrl = $this->staticUrlService::findOne(['ident' => $conversionInfo->token]);
            if (!$staticUrl) {
                return [false, 'Token不存在', 500];
            }
            if ($this->staticConversion::findOne([
                'ip'   => $this->responseUtils::ipToInt($this->request->getUserIP()),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id
            ])) {
                return [false, 'Ip已经被记录', 500];
            }
            //访问记录
            $staticConversionPo = new StaticConversionPo;
            $staticConversionPo->wxh = $conversionInfo->wxh;
            $staticConversionPo->referer = $_SERVER['HTTP_REFERER'] ?? '';
            $staticConversionPo->agent = $_SERVER['HTTP_USER_AGENT'];
            $staticConversionPo->createtime = $_SERVER['REQUEST_TIME'];
            /* @var $ipLocationUtils IpLocationUtils */
            $ipLocationUtils = new $this->ipLocationUtils;
            $ipLocationUtils = $ipLocationUtils->getlocation(long2ip($this->responseUtils::ipToInt($this->request->getUserIP())));
            $staticConversionPo->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $staticConversionPo->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $staticConversionPo->date = strtotime(date('Y-m-d'));
            $staticConversionPo->page = $staticUrl->url;
            if ($staticUrl->pcurl && !$this->requestUtils::requestFromMobile()) {
                $staticConversionPo->page = $staticUrl->pcurl;
            }
            $staticConversionPo->ip = $this->responseUtils::ipToInt($this->request->getUserIP());
            $staticConversionPo->u_id = $staticUrl->id;
            $this->staticConversion::insert($staticConversionPo);
            //转化数增加
            $this->staticServiceConversionsService::increasedConversions($staticUrl);
            //用户行为统计接口
            /* @var $userActionsAip UserActionsAip */
            $userActionsAip = new $this->userActionsController;
            $userActionsAip->add();
            return [true, '操作成功!', 200];
        } catch (ValidateException|Exception|TencentMarketingApiException $e) {
            return [false, $e->getMessage(), $e->getCode()];
        }
    }
}