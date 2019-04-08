<?php

namespace app\modules\v1\rest;

use app\modules\v1\common\exception\ValidateException;
use app\modules\v1\domain\StaticConversion;
use app\modules\v1\domain\StaticUrl;
use app\modules\v1\domain\vo\ConversionInfo;
use app\modules\v1\service\impl\StaticConversionImpl;
use app\modules\v1\service\impl\StaticUrlImpl;
use app\modules\v1\service\StaticConversionService;
use app\modules\v1\service\StaticUrlService;
use app\modules\v1\utils\IpLocationUtils;
use app\modules\v1\utils\ResponseUtils;
use app\modules\v1\utils\SourceDetectionUtil;
use app\modules\v1\utils\RequestUtils;

/**
 * Landing page conversions（copy WeChat）.
 * Class ConversionController
 *
 * @property SourceDetectionUtil $sourceDetectionUtil
 * @property IpLocationUtils $ipLocationUtils
 * @property RequestUtils $requestUtils
 * @property ConversionInfo $conversionInfo
 * @property StaticUrlService $staticUrlService
 * @property StaticConversionService $StaticConversion
 * @package app\modules\v1\rest
 * @author: lirong
 */
class ConversionController extends RestController
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
    private $StaticConversion = StaticConversionImpl::class;

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
            if ($this->StaticConversion::findOne([
                'ip'   => $this->responseUtils::ipToInt($this->request->getUserIP()),
                'date' => strtotime(date('Y-m-d')),
                'u_id' => $staticUrl->id
            ])) {
                $this->transaction->rollBack();
                return [false, 'Ip已经被记录', 500];
            }

            /* @var $StaticConversion StaticConversion */
            $StaticConversion = new $this->StaticConversion;
            $StaticConversion->wxh = $conversionInfo->wxh;
            $StaticConversion->referer = $_SERVER['HTTP_REFERER'];
            $StaticConversion->agent = $_SERVER['HTTP_USER_AGENT'];
            $StaticConversion->createtime = $_SERVER['REQUEST_TIME'];
            /* @var $ipLocationUtils IpLocationUtils */
            $ipLocationUtils = new $this->ipLocationUtils;
            $ipLocationUtils = $ipLocationUtils->getlocation(long2ip($this->responseUtils::ipToInt($this->request->getUserIP())));
            $StaticConversion->country = iconv('gbk', 'utf-8', $ipLocationUtils['country']) ?: '';
            $StaticConversion->area = iconv('gbk', 'utf-8', $ipLocationUtils['area']) ?: '';
            $StaticConversion->date = strtotime(date('Y-m-d'));
            $StaticConversion->page = $staticUrl->url;
            if ($staticUrl->pcurl && !$this->requestUtils::requestFromMobile()) {
                $StaticConversion->page = $staticUrl->pcurl;
            }
            $StaticConversion->ip = $this->responseUtils::ipToInt($this->request->getUserIP());
            $StaticConversion->u_id = $staticUrl->id;
            $this->StaticConversion::insert($StaticConversion);
            return [true, '操作成功!', 200];
        } catch (ValidateException $e) {
            $this->transaction->rollBack();
            return [false, $e->getMessage(), $e->getCode()];
        }
    }
}