<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\IndexImgDo;
use app\modules\v2\operateDept\domain\dto\IndexImgDto;
use app\modules\v2\operateDept\domain\dto\IndexImgForm;
use Yii;
use yii\db\Exception;

class IndexImgEntiy extends IndexImgDo
{
    /**
     * 创建设计中心实体
     * @param IndexImgForm $indexImgForm
     * @return bool
     * @author ctl
     */
    public function createEntity(IndexImgForm $indexImgForm): bool
    {
        $model = new self;
        $model->setAttributes($indexImgForm->getAttributes());
        if ($picture_address = $indexImgForm->upload()) {
            $model->picture_address = '/uploads/designCenter/index-img/' . $picture_address;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
        return $model->save();
    }

    /**
     * 设计中心详情实体
     * @param int $id
     * @return array
     * @author: ctl
     */

    public function detailEntity(int $id): array
    {
        $model = self::findOne($id);
        return $model->attributes;
    }

    /**
     * 更新设计中心实体
     * @param IndexImgForm $indexImgForm
     * @return bool
     * @throws Exception
     * @author: ctl
     */

    public function updateEntity(IndexImgForm $indexImgForm): bool
    {
        $model = self::findOne($indexImgForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($indexImgForm->getAttributes());
        if($picture_address = $indexImgForm->upload()){
            $model->picture_address = '/uploads/designCenter/index-img/' . $picture_address;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
        return $model->save();
    }

    /**
     * 删除首页图的实体
     * @param int $id
     * @return int
     * @author: ctl
     */
    public function deleteEntity(int $id): int
    {
        $delPath = $this->detailEntity($id);
        unlink(Yii::$app->basePath . '/web'.$delPath['picture_address'] );
        return self::deleteAll(['id' => $id]);
    }

    /**
     * @param IndexImgDto $indexImgDto
     * @return bool
     * @author: ctl
     */

    public function auditEntity(IndexImgDto $indexImgDto): bool
    {
        $model = self::findOne($indexImgDto->id);
        $model->setAttributes($indexImgDto->toArray(),false);
        $model->audit_time = time();
        return $model->save();
    }

    /**
     * 查看首页图片实体
     * @param int $id
     * @return string
     * @author: ctl
     */
    public function readEntity(int $id): string
    {
        $model = self::findOne($id);
        return $model->picture_address;
    }
}
