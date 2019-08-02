<?php
declare(strict_types=1);

namespace app\modules\v2\oauth\web;

use app\api\tencentMarketingApi\userActionSets\api\UserActionSetsApi;
use app\api\toutiaoMarketingApi\oauth\dto\TokenRequestDto;
use app\api\toutiaoMarketingApi\oauth\service\Oauth;
use app\common\exception\ToutiaoMarketingApiException;
use app\common\utils\UrlUtils;
use app\common\web\WebBaseController;
use app\modules\v2\oauth\domain\dto\AuthorizeRequestDto;
use app\modules\v2\oauth\domain\dto\AuthorizeResponseDto;
use app\modules\v2\oauth\domain\enum\AuthorizeEnum;
use app\modules\v2\oauth\domain\enum\GrantTypeEnum;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * 鉴权控制器
 * Class UserActionController
 *
 * @property UrlUtils          $urlUtils
 * @property Oauth             $oauth
 * @property UserActionSetsApi $actionSetsApi
 * @package app\modules\v1\oauth\rest
 */
class AuthorizeController extends WebBaseController
{
    /* @var UrlUtils $urlUtils */
    public $urlUtils;
    /* @var Oauth $oauth */
    public $oauth;

    public function __construct($id, $module,
                                UrlUtils $urlUtils,
                                Oauth $oauth,
                                $config = [])
    {
        $this->urlUtils = $urlUtils;
        $this->oauth    = $oauth;
        parent::__construct($id, $module, $config);
    }

    /**
     * Oauth2.0鉴权请求
     * @author zhuozhen
     */
    public function actionAuthorizeApi() : void
    {
        $authorizeRequestDto = new AuthorizeRequestDto();
        $authorizeRequestDto->app_id = Yii::$app->params['oauth']['toutiao_marketing_api']['app_id'];
        $authorizeRequestDto->redirect_uri = Yii::$app->params['oauth']['toutiao_marketing_api']['redirect_uri'];
        //TODO 用于验证
        $authorizeRequestDto->state = '';
        $authorizeRequestDto->scope = AuthorizeEnum::USER_ACTIONS;
        //重定向到头条
        $this->redirect(Yii::$app->params['oauth']['toutiao_marketing_api']['authorize_url'] .
            $this->urlUtils->getRequestParamsFromGet($authorizeRequestDto->attributes))->send();
    }

    /**
     * Oauth2.0鉴权回调
     * @author zhuozhen
     */
    public function actionAuthorizeCallBack() :void
    {
        $authorizeResponseDto = new AuthorizeResponseDto();
        $tokenRequestDto = new TokenRequestDto();
        $authorizeResponseDto->setAttributes($this->request->get());
        $tokenRequestDto->app_id = Yii::$app->params['oauth']['toutiao_marketing_api']['app_id'];
        $tokenRequestDto->secret = Yii::$app->params['oauth']['toutiao_marketing_api']['secret'];
        $tokenRequestDto->grant_type = GrantTypeEnum::AUTH_CODE;
        $tokenRequestDto->auth_code = $authorizeResponseDto->auth_code;
        try {
            $this->oauth->applyToken($tokenRequestDto);
        } catch (GuzzleException|ToutiaoMarketingApiException $e) {
            Yii::info($e->getMessage());
        }
        $this->render('xxx');
    }
}