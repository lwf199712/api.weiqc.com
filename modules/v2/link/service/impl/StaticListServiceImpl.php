<?php
declare(strict_types=1);

namespace app\modules\v2\link\service\impl;

use app\modules\v2\link\domain\dto\StaticUrlDto;
use app\modules\v2\link\domain\repository\StaticUrlDoManager;
use app\modules\v2\link\service\StaticListService;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

class StaticListServiceImpl extends BaseObject implements StaticListService
{
    /** @var StaticUrlDoManager  */
    public $staticUrlDoManager;

    public function __construct( StaticUrlDoManager $staticUrlDoManager,
                                 $config = [])
    {
        $this->staticUrlDoManager = $staticUrlDoManager;
        parent::__construct($config);
    }


    /**
     * 列表数据提供器
     * @param StaticUrlDto $staticUrlDto
     * @return mixed
     */
    public function listDataProvider(StaticUrlDto $staticUrlDto) : ActiveDataProvider
    {
        return $this->staticUrlDoManager->listStaticUrl($staticUrlDto);
    }

    /**
     * 指定数据提供器
     * @param int $id
     * @return mixed
     * @author liruizhao
     */
    public function getDetailProvider(int $id)
    {
        // TODO: Implement getDetailProvider() method.
    }
}