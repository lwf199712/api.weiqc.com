<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\aggregate;

use app\models\dataObject\IndexImgDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterDto;
use app\modules\v2\operateDept\domain\dto\DesignCenterForm;
use app\modules\v2\operateDept\domain\dto\IndexImgDto;
use app\modules\v2\operateDept\domain\dto\IndexImgForm;
use app\modules\v2\operateDept\domain\repository\IndexImgDoManager;
use app\modules\v2\operateDept\domain\entity\IndexImgEntiy as IndexImgAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class DesignCenterAggregate
 * @property IndexImgDto             $indexImgDto
 * @property IndexImgDoManager       $indexImgDoManager
 * @property IndexImgAggregateRoot   $indexImgAggregateRoot
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class IndexImgAggregate extends BaseObject
{
    private $indexImgAggregateRoot;
    /** @var IndexImgDto */
    private $indexImgDto;
    /** @var IndexImgDoManager */
    private $indexImgDoManager;

    public function __construct(
        IndexImgDto     $indexImgDto,
        IndexImgDoManager       $indexImgDoManager,
        IndexImgAggregateRoot  $indexImgAggregateRoot,
        $config = [])
    {
        $this->indexImgDto = $indexImgDto;
        $this->indexImgAggregateRoot = $indexImgAggregateRoot;
        $this->indexImgDoManager = $indexImgDoManager;
        parent::__construct($config);
    }

    /**
     * @params IndexImgDto $indexImgDto
     * @return array
     * @author ctl
     */
    public function listIndexImg(IndexImgDto $indexImgDto): array
    {
        $list['lists'] = $this->indexImgDoManager->listDataProvider($indexImgDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = $pictureUrl[4];
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = $pictureUrl[0].'.'.$pictureTwo[1];
        }
        $list['totalCount'] = $this->indexImgDoManager->listDataProvider($indexImgDto)->getTotalCount();
        return $list;
    }

    /**
     * @param IndexImgForm $indexImgForm
     * @return bool
     * @throws Exception
     * @author ctl
     */
    public function createIndexImg(IndexImgForm $indexImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $indexImgForm->imageFile = $imageFile;
        $result = $this->indexImgAggregateRoot->createEntity($indexImgForm);
        if ($result === false) {
            throw new Exception('新增设计中心核实失败');
        }
        return $result;
    }

    /**
     *
     * @param int $indexImgId
     * @return array
     * @author: ctl
     */
    public function detailIndexImg(int $indexImgId): array
    {
        return $this->indexImgAggregateRoot->detailEntity($indexImgId);
    }

    /**
     * @param IndexImgForm $indexImgForm
     * @return bool
     * @throws Exception
     * @author ctl
     */
    public function updateIndexImg(IndexImgForm $indexImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $indexImgForm->imageFile = $imageFile;
        $status = $this->indexImgAggregateRoot->detailEntity((int)$indexImgForm->id);
        if ($status['audit_status'] === 1){
            throw new Exception('审核状态为已通过');
        }
        $result = $this->indexImgAggregateRoot->updateEntity($indexImgForm);
        if ($result === false) {
            throw new Exception('更新设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param int $indexImgiId
     * @return int
     * @author ctl
     */
    public function deleteIndexImg(int $id): int
    {
        return $this->indexImgAggregateRoot->deleteEntity($id);
    }

    /**
     *
     * @param IndexImgDto $indexImgDto
     * @return bool
     * @throws Exception
     * @author: weifeng
     */

    public function auditIndexImg(IndexImgDto $indexImgDto): bool
    {
        $result = $this->indexImgAggregateRoot->auditEntity($indexImgDto);
        if ($result === false) {
            throw new Exception('审核失败');
        }
        return $result;
    }

    /**
     *
     * @param int $indexImgId
     * @return string
     * @author: CTL
     */

    public function readIndexImg(int $indexImgId): string
    {
        return $this->indexImgAggregateRoot->readEntity($indexImgId);
    }
}