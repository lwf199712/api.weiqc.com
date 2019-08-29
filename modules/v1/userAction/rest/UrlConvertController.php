<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\rest;


use app\common\exception\RedisException;
use app\common\rest\RestBaseController;
use app\common\utils\ArrayUtils;
use app\common\utils\IpLocationUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use app\common\utils\SourceDetectionUtil;
use app\daemon\course\urlConvert\domain\dto\RedisUrlConvertDto;
use app\modules\v1\userAction\domain\vo\UrlConvertRequestVo;
use app\modules\v1\userAction\service\UserActionCache;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use Exception;
use yii\web\Cookie;
use yii\web\Response;


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
 * @property  RedisUrlConvertDto         $redisUrlConvertDto
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
    /** @var RedisUrlConvertDto */
    protected $redisUrlConvertDto;

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
     * @param RedisUrlConvertDto          $redisUrlConvertDto
     * @param array                       $config
     */
    public function __construct($id, $module,
                                UserActionStaticHitsService $staticHitsService,
                                UserActionStaticUrlService $staticUrlService,
                                UserActionCache $userActionCache,
                                SourceDetectionUtil $sourceDetectionUtil,
                                ResponseUtils $responseUtils,
                                IpLocationUtils $ipLocationUtils,
                                RequestUtils $requestUtils,
                                RedisUrlConvertDto $redisUrlConvertDto,
                                $config = [])
    {
        $this->staticHitsService  = $staticHitsService;
        $this->staticUrlService   = $staticUrlService;
        $this->userActionCache    = $userActionCache;
        $this->redisUrlConvertDto = $redisUrlConvertDto;
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


    /**
     * @return Response
     * @author zhuozhen
     */
    public function actionConvert(): Response
    {
        try {
            $this->sourceDetectionUtil->crossDomainDetection();
            $conversionInfo = new UrlConvertRequestVo();
            $conversionInfo->setAttributes($this->request->get());
            //检查落地页是否存在
            $staticUrl = $this->staticUrlService->findOne(['ident' => $conversionInfo->token]);
            if (!$staticUrl) {
                $this->response->statusCode = 500;
                $this->response->content = '找不到该链接';
                return $this->response;
            }
            $linkUrl                              = ($this->sourceDetectionUtil->mobileDetection() && $staticUrl->pcurl) ? $staticUrl->pcurl : $staticUrl->url;
            $this->redisUrlConvertDto->u_id       = $staticUrl->id;
            $this->redisUrlConvertDto->referer    = $this->request->getReferrer();
            $this->redisUrlConvertDto->ip         = $this->responseUtils->ipToInt($this->request->getUserIP());
            $this->redisUrlConvertDto->agent      = addslashes($_SERVER['HTTP_USER_AGENT']);
            $this->redisUrlConvertDto->createtime = $_SERVER['REQUEST_TIME'];

            if ($this->redisUrlConvertDto->ip) {
                $today    = strtotime(date('Y-m-d'));
                $checkIp  = $this->staticHitsService->findOne(['ip' => $this->redisUrlConvertDto->ip, 'date' => $today, 'u_id' => $this->redisUrlConvertDto->u_id]);
                $verifyIp = $checkIp ? false : true;

                //检查Cookie
                $cookieName = 'static_url_' . $staticUrl->ident;
                $cookie     = $this->request->cookies->get($cookieName);
                if ($cookie) {
                    $visitDate    = strtotime(date('Y-m-d', $cookie->value['createtime']));
                    $verifyCookie = ($cookie->value['createtime'] > 0 && $today > $visitDate);
                } else {
                    $verifyCookie = true;
                }
                $cookie = $this->response->cookies;
                $cookie->add(new Cookie([
                    'name'  => $cookieName,
                    'value' => ArrayUtils::attributesAsMap($this->redisUrlConvertDto),
                ]));

                $ipLocationUtils            = $this->ipLocationUtils->getlocation(long2ip($this->redisUrlConvertDto->ip));
                $ipLocationUtils['country'] = iconv('gbk', 'utf-8', $ipLocationUtils['country']);
                $ipLocationUtils['area']    = iconv('gbk', 'utf-8', $ipLocationUtils['area']);

                $this->redisUrlConvertDto->country = $ipLocationUtils['country'] ? addslashes($ipLocationUtils['country']) : '';
                $this->redisUrlConvertDto->area    = $ipLocationUtils['area'] ? addslashes($ipLocationUtils['area']) : '';
                $this->redisUrlConvertDto->date    = $today;
                $this->redisUrlConvertDto->page    = $linkUrl;

                if ($verifyIp) {
                    $this->userActionCache->addUrConvertHits($this->redisUrlConvertDto);
                }
                if ($verifyCookie) {
                    $this->userActionCache->addUrConvertClient($this->redisUrlConvertDto);
                }
                $this->userActionCache->addUrConvertVisit($this->redisUrlConvertDto);

                $linkUrl .= (strpos($linkUrl, '?') === false ? '?token=' : '&token=') . $staticUrl->ident;

                $this->response->statusCode = 200;
                return $this->response->redirect($linkUrl);
            }
        } catch (Exception|RedisException $e) {
            $this->response->statusCode = $e->getCode();
            $this->response->content = $e->getMessage();
            return $this->response;
        }
    }

}