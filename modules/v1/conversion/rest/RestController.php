<?php

namespace app\modules\v1\conversion\rest;

use app\common\rest\RestBaseController;
use app\exception\ValidateException;
use app\modules\v1\conversion\domain\StaticConversion;
use app\modules\v1\conversion\domain\StaticUrl;
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
use yii\db\Exception;

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
                $this->transaction->rollBack();
                return [false, 'Token不存在', 500];
            }
            if ($this->staticConversion::findOne([
                'ip'   => $this->responseUtils::ipToInt($this->request->getUserIP()),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id
            ])) {
                $this->transaction->rollBack();
                return [false, 'Ip已经被记录', 500];
            }

            $staticConversion = new StaticConversion;
            $staticConversion->wxh = $conversionInfo->wxh;
            $staticConversion->referer = $_SERVER['HTTP_REFERER'] ?? '';
            $staticConversion->agent = $_SERVER['HTTP_USER_AGENT'];
            $staticConversion->createtime = $_SERVER['REQUEST_TIME'];
            /* @var $ipLocationUtils IpLocationUtils */
            $ipLocationUtils = new $this->ipLocationUtils;
            $ipLocationUtils = $ipLocationUtils->getlocation(long2ip($this->responseUtils::ipToInt($this->request->getUserIP())));
            $staticConversion->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $staticConversion->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $staticConversion->date = strtotime(date('Y-m-d'));
            $staticConversion->page = $staticUrl->url;
            if ($staticUrl->pcurl && !$this->requestUtils::requestFromMobile()) {
                $staticConversion->page = $staticUrl->pcurl;
            }
            $staticConversion->ip = $this->responseUtils::ipToInt($this->request->getUserIP());
            $staticConversion->u_id = $staticUrl->id;
            $this->staticConversion::insert($staticConversion);
            $this->staticServiceConversionsService::increasedConversions($staticUrl);
            //TODO 腾讯api

            return [true, '操作成功!', 200];
        } catch (ValidateException|Exception $e) {
            $this->transaction->rollBack();
            return [false, $e->getMessage(), $e->getCode()];
        }
    }
}