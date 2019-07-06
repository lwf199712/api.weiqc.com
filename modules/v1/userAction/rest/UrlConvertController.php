<?php


namespace app\modules\v1\userAction\rest;


use app\common\exception\RedisException;
use app\common\rest\RestBaseController;
use app\common\utils\IpLocationUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use app\common\utils\SourceDetectionUtil;
use app\daemon\course\conversion\domain\dto\RedisUrlConvertDto;
use app\modules\v1\userAction\domain\vo\UrlConvertRequestVo;
use app\modules\v1\userAction\service\UserActionCache;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use Exception;
use yii\web\Cookie;


/**
 * 短链转长链接口
 * Class UrlConvertController
 * @property UserActionStaticHitsService $staticHitsService
 * @property  UserActionStaticUrlService $staticUrlService,
 * @property UserActionCache             userActionCache
 * @property IpLocationUtils             $ipLocationUtils
 * @property RequestUtils                $requestUtils
 * @property ResponseUtils               $responseUtils
 * @property SourceDetectionUtil         $sourceDetectionUtil
 * @package app\modules\v1\userAction\rest
 */
class UrlConvertController extends RestBaseController
{
    /** @var UserActionStaticHitsService */
    protected $staticHitsService;
    /** @var  UserActionStaticUrlService */
    protected $staticUrlService;
    /** @var UserActionCache */
    protected $userActionCache;
    /* @var ResponseUtils */
    protected $responseUtils;
    /* @var SourceDetectionUtil */
    protected $sourceDetectionUtil;
    /* @var IpLocationUtils */
    protected $ipLocationUtils;
    /* @var RequestUtils */
    protected $requestUtils;

    /**
     * UrlConvertController constructor.
     * @param                             $id
     * @param                             $module
     * @param UserActionStaticHitsService $staticHitsService
     * @param UserActionStaticUrlService  $staticUrlService
     * @param UserActionCache             $userActionCache ,
     * @param SourceDetectionUtil         $sourceDetectionUtil
     * @param ResponseUtils               $responseUtils
     * @param IpLocationUtils             $ipLocationUtils
     * @param RequestUtils                $requestUtils
     * @param                             $config
     */
    public function __construct($id, $module,
                                UserActionStaticHitsService $staticHitsService,
                                UserActionStaticUrlService $staticUrlService,
                                UserActionCache $userActionCache,
                                SourceDetectionUtil $sourceDetectionUtil,
                                ResponseUtils $responseUtils,
                                IpLocationUtils $ipLocationUtils,
                                RequestUtils $requestUtils,
                                $config = [])
    {
        $this->staticHitsService = $staticHitsService;
        $this->staticUrlService  = $staticUrlService;
        $this->userActionCache   = $userActionCache;
        //工具类
        $this->responseUtils       = $responseUtils;
        $this->sourceDetectionUtil = $sourceDetectionUtil;
        $this->ipLocationUtils     = $ipLocationUtils;
        $this->requestUtils        = $requestUtils;
        $this->responseUtils       = $responseUtils;
        parent::__construct($id, $module, $config);
    }


    public function verbs(): array
    {
        return [
            'convert' => ['GET', 'HEAD'],
        ];
    }


    public function actionConvert(): array
    {
        try {
            $this->sourceDetectionUtil->crossDomainDetection();
            $conversionInfo = new UrlConvertRequestVo();
            $conversionInfo->setAttributes($this->request->get());
            //检查落地页是否存在
            $staticUrl = $this->staticUrlService->findOne(['ident' => $conversionInfo->token]);
            if (!$staticUrl) {
                return ['Token不存在', 500];
            }
            $linkUrl                        = ($this->sourceDetectionUtil->mobileDetection() && $staticUrl->pcurl) ? $staticUrl->pcurl : $staticUrl->url;
            $redisUrlConvertDto             = new RedisUrlConvertDto();
            $redisUrlConvertDto->u_id       = $staticUrl->id;
            $redisUrlConvertDto->referer    = $this->request->getReferrer();
            $redisUrlConvertDto->ip         = $this->responseUtils->ipToInt($this->request->getUserIP());
            $redisUrlConvertDto->agent      = addslashes($_SERVER['HTTP_USER_AGENT']);
            $redisUrlConvertDto->createtime = $_SERVER['REQUEST_TIME'];

            if ($redisUrlConvertDto->ip) {
                $today    = strtotime(date('Y-m-d', time()));
                $checkIp  = $this->staticHitsService->findOne(['ip' => $redisUrlConvertDto->ip, 'date' => $today, 'u_id' => $redisUrlConvertDto->u_id]);
                $verifyIp = $checkIp ? false : true;

                //检查Cookie
                $cookieName = 'static_url_' . $staticUrl->ident;
                $cookie     = $this->request->cookies->get($cookieName);
                if ($cookie) {
                    $visitDate    = strtotime(date("Y-m-d", $cookie->createtime));
                    $verifyCookie = ($cookie->createtime > 0 && $today > $visitDate) ? true : false;
                } else {
                    $verifyCookie = true;
                }
                $cookie = $this->response->cookies;
                $cookie->add(new Cookie([
                    'name'  => $cookieName,
                    'value' => $redisUrlConvertDto,
                ]));

                $ipLocationUtils            = $this->ipLocationUtils->getlocation(long2ip($redisUrlConvertDto->ip));
                $ipLocationUtils['country'] = iconv("gbk", "utf-8", $ipLocationUtils['country']);
                $ipLocationUtils['area']    = iconv("gbk", "utf-8", $ipLocationUtils['area']);

                $redisUrlConvertDto->country = $ipLocationUtils['country'] ? addslashes($ipLocationUtils['country']) : '';
                $redisUrlConvertDto->area    = $ipLocationUtils['area'] ? addslashes($ipLocationUtils['area']) : '';
                $redisUrlConvertDto->date    = $today;
                $redisUrlConvertDto->page    = $linkUrl;

                if ($verifyIp) {
                    $this->userActionCache->addUrConvertHits($redisUrlConvertDto);
                }
                if ($verifyCookie) {
                    $this->userActionCache->addUrConvertClient($redisUrlConvertDto);
                }
                $this->userActionCache->addUrConvertVisit($redisUrlConvertDto);

                $linkUrl .= (strpos($linkUrl, '?') === false ? '?token=' : '&token=') . $staticUrl->ident;

                $this->response->redirect($linkUrl);
            }
        } catch (Exception|RedisException $e) {
            return [$e->getMessage(), $e->getCode()];
        }
    }

}