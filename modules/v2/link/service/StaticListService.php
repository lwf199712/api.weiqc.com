<?php
declare(strict_types=1);

namespace app\modules\v2\link\service;


use app\modules\v2\link\domain\dto\StaticUrlDto;
use yii\data\ActiveDataProvider;

interface StaticListService
{
    /**
     * 列表数据提供器
     * @param StaticUrlDto $staticUrlDto
     * @return mixed
     */
    public function listDataProvider(StaticUrlDto $staticUrlDto) : ActiveDataProvider;

    /**
     * 指定数据提供器
     * @param int $id
     * @return mixed
     * @author liruizhao
     */
    public function getDetailProvider(int $id);
}