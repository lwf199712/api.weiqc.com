<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\service;

use app\modules\v2\operateDept\domain\dto\DesignCenterImageForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageQuery;

interface DesignCenterImageService
{
    /**
     * 图片列表
     * @param DesignCenterImageQuery $designCenterImageQuery
     * @return mixed
     * @author: weifeng
     */
    public function listImage(DesignCenterImageQuery $designCenterImageQuery): array;

    /**
     * 创建图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return mixed
     * @author: weifeng
     */
    public function createImage(DesignCenterImageForm $designCenterImageForm);

    /**
     * 更新图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return mixed
     * @author: weifeng
     */
    public function updateImage(DesignCenterImageForm $designCenterImageForm);

    /**
     * 删除图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return mixed
     * @author: weifeng
     */
    public function deleteImage(DesignCenterImageForm $designCenterImageForm);

    /**
     * 审核图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return mixed
     * @author: weifeng
     */

    public function auditImage(DesignCenterImageForm $designCenterImageForm);

    /**
     * 查看图片详情
     * @param $id
     * @return mixed
     * @author: weifeng
     */
    public function viewImage(int $id): array;

    /**
     * 上传图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @param string $dirName
     * @return string
     * @author: weifeng
     */
    public function uploadImage(DesignCenterImageForm $designCenterImageForm, string $dirName): string;

}