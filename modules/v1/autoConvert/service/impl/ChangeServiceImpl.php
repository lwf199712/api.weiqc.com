<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service\impl;

use app\modules\v1\autoConvert\service\AutoConvertStaticConversionService;
use app\modules\v1\autoConvert\service\AutoConvertStaticUrlService;
use app\modules\v1\autoConvert\service\ChangeService;
use Throwable;
use Yii;
use yii\base\BaseObject;

class ChangeServiceImpl extends BaseObject implements ChangeService
{

    /**
     * 转粉程序
     * @param string $currentDept
     * @param string $lackFansDept
     * @param AutoConvertStaticUrlService $autoConvertStaticUrlService
     * @param AutoConvertStaticConversionService $autoConvertStaticConversionService
     * @return bool
     * @author zhuozhen
     */
    public function __invoke(string $currentDept,
                             string $lackFansDept,
                             AutoConvertStaticUrlService $autoConvertStaticUrlService,
                             AutoConvertStaticConversionService $autoConvertStaticConversionService): bool
    {
        $urlSet = $autoConvertStaticUrlService->getServiceUrl($currentDept);

        if (empty($urlSet)){
            return false;
        }
        foreach ($urlSet as $key => $value){
            $url = $value['url'];
            $pcUrl = $value['pcurl'];
            if (strpos($url, 'wxh') && strpos($pcUrl, 'wxh')) {
                $url = substr($url, 0, strrpos($url, '?'));
                $pcUrl = substr($pcUrl, 0, strrpos($pcUrl, '?'));
            }
            $url = $url . '?wxh=' . $lackFansDept;
            $pcUrl = $pcUrl . '?wxh=' . $lackFansDept;

            $transaction = Yii::$app->db->beginTransaction();
            try {
               $rows = $autoConvertStaticUrlService->updateUrl((int)$value['url_id'], $url, $pcUrl, $currentDept, $lackFansDept);
               $rows2 = $autoConvertStaticConversionService->updateService((int)$value['service_id'], $lackFansDept);
                $transaction->commit();
                Yii::info('自动转粉：' . $currentDept . '切换为' . $lackFansDept . '成功！');
                var_dump($rows.'====='.$rows2);
            } catch (Throwable $e) {
                Yii::info('自动转粉系统切换公众号时候catch到了异常，异常信息为：' . $e->getMessage());
                $transaction->rollBack();
                var_dump($e->getMessage());
            }
        }
        return true;
    }
}