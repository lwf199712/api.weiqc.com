<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\ZuanzhanImgDo;
use app\modules\v2\operateDept\domain\dto\ZuanzhanImgDto;
use app\modules\v2\operateDept\domain\dto\ZuanzhanImgForm;
use Yii;
use yii\db\Exception;

class ZuanzhanImgEntity extends ZuanzhanImgDo
{

    /**
     * 创建设计中心实体
     * @param ZuanzhanImgForm $zuanzhanImgForm
     * @return bool
     * @author ctl
     */
    public function createEntity(ZuanzhanImgForm $zuanzhanImgForm): bool
    {
        $model = new self;
        $model->setAttributes($zuanzhanImgForm->getAttributes());
        if ($picture_address = $zuanzhanImgForm->upload()) {
            $model->picture_address = '/uploads/designCenter/zuanzhan-img/' . $picture_address;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
        return $model->save();
    }

    /**
     * 更新设计中心实体
     * @param ZuanzhanImgForm $zuanzhanImgForm
     * @return bool
     * @throws Exception
     * @author: ctl
     */

    public function updateEntity(ZuanzhanImgForm $zuanzhanImgForm): bool
    {
        $model = self::findOne($zuanzhanImgForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($zuanzhanImgForm->getAttributes());
        if($picture_address = $zuanzhanImgForm->upload()){
            $model->picture_address = '/uploads/designCenter/zuanzhan-img/' . $picture_address;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
        return $model->save();
    }

    /**
     * 删除设计中心实体
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
     *
     * @param ZuanzhanImgDto $zuanzhanImgDto
     * @return bool
     * @author: ctl
     */

    public function auditEntity(ZuanzhanImgDto $zuanzhanImgDto): bool
    {
        $model = self::findOne($zuanzhanImgDto->id);
        $model->setAttributes($zuanzhanImgDto->toArray(),false);
        $model->audit_time = time();
        return $model->save();
    }

    /**
     * 查看设计中心图片实体
     * @param int $id
     * @return string
     * @author: ctl
     */
    public function readEntity(int $id): string
    {
        $model = self::findOne($id);
        return $model->picture_address;
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



}