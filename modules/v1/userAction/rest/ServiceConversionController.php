<?php
declare(strict_types=1);

namespace app\modules\v1\userAction\rest;


use app\common\rest\RestBaseController;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\userAction\domain\entity\StaticServiceConversionEntity;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use Yii;
use yii\base\Exception;

/**
 * Class ServiceConversionController
 * @@property  UserActionStaticUrlService $userActionStaticUrlService
 * @package app\modules\v1\userAction\rest
 */
class ServiceConversionController extends RestBaseController
{
    /** @var UserActionStaticUrlService $userActionStaticUrlService */
    protected $userActionStaticUrlService;

    public function __construct($id, $module,
                                UserActionStaticUrlService $userActionStaticUrlService,
                                $config = [])
    {
        $this->userActionStaticUrlService = $userActionStaticUrlService;
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
            'service' => ['POST', 'HEAD'],
        ];
    }

    /**
     *
     * @return array
     * @author zhuozhen
     */
    public function service(): array
    {
        $origin = $this->request->getOrigin();
        $host   = $this->request->getHostName();
        if (in_array($host, Yii::$app->params['params']['cross_domain'], false)) {
            header('Access-Control-Allow-Origin:' . $origin);
        }
        if (empty($this->request->post('token'))) {
            return [$origin . $host . '无法获取到token！', 406];
        }
        /** @var StaticUrlDo $staticUrl */
        $staticUrl = $this->userActionStaticUrlService->findOne(['ident' => $this->request->post('token')]);
        if ($staticUrl === null) {
            return [$origin . $host . 'token值验证失败！', 406];
        }
        $staticServiceConversionEntity = StaticServiceConversionEntity::findOne(['u_id' => $staticUrl->id]);
        if ($staticServiceConversionEntity === null) {
            return ['链接公众号没有转换模式', 406];
        }
        $staticServiceConversionEntity->scenario = StaticServiceConversionEntity::SERVICE_CONVERSION;
        if ($staticServiceConversionEntity->validate() === false){
            return ['验证失败',406,$staticServiceConversionEntity->getErrors()];
        }
        try {
            $staticServiceConversionEntity->parserServiceInfo();
            $convertedAccount = $staticServiceConversionEntity->getResultOfConvert($staticServiceConversionEntity);
            if ($convertedAccount !== $staticServiceConversionEntity->service) {
                $finialAccount = ($this->userActionStaticUrlService->updateService($staticUrl->id,$convertedAccount) &&
                $staticServiceConversionEntity->updateConversions($staticUrl->id,$convertedAccount)) === true ?
                    $convertedAccount : $staticServiceConversionEntity->service;
            }
            return ['成功',200 , $finialAccount ?? $convertedAccount];
        } catch (Exception $exception) {
            return [$exception->getMessage(), 500];
        }

    }
}