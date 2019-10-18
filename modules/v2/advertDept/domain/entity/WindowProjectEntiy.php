<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/15
 * Time: 10:43
 */

namespace app\modules\v2\advertDept\domain\entity;


use app\models\dataObject\WindowProjectDo;
use app\modules\v2\advertDept\domain\dto\WindowProjectDto;
use app\modules\v2\advertDept\domain\dto\WindowProjectForm;
use app\modules\v2\advertDept\domain\factory\WindowProjectFactory;
use Exception;

class WindowProjectEntiy extends WindowProjectDo
{

    /**
     * 创建橱窗项目实体
     * @param WindowProjectForm $windowProjectForm
     * @return bool
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/16 16:30
     */
    public function createEntity(WindowProjectForm $windowProjectForm): bool
    {
        $windowProjectFactory = new WindowProjectFactory();
        $msg = true;
        foreach (json_decode($windowProjectForm->getAttributes(['voArr'])['voArr'], true) as $value){
            $model = new self;
            $model->setAttributes($windowProjectForm->getAttributes());
            try {
                if (!$windowProjectFactory->creator($model, $value)){
                    $msg = false;
                    break;
                }
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry')){
                    throw new Exception('已存在相同的产品：' . $windowProjectForm->product_name .
                        '、时间段：' . $value['period'] . '~' . ($value['period']+1) . '、账号的数据：' .
                        $windowProjectForm->account_and_id . '，请检查好后再重新录入');
                }
                throw new Exception($e->getMessage());
            }
        }
        return $msg;
    }

    /**
     * 橱窗项目实体详情
     * @param int $id
     * @return WindowProjectEntiy|array|\yii\db\ActiveRecord|null
     * author: pengguochao
     * Date Time 2019/10/17 9:08
     */
    public function detailEntity(int $id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    /**
     * 根据产品名称查找一个橱窗项目实体
     * @param string $productName
     * @return WindowProjectEntiy|array|\yii\db\ActiveRecord|null
     * author: pengguochao
     * Date Time 2019/10/18 10:26
     */
    public function findEntityByProductName(string $productName)
    {
        return self::find()->where(['product_name' => $productName])->one();
    }
    /**
     * 更新橱窗项目实体
     * @param WindowProjectForm $windowProjectForm
     * @return bool
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/17 12:20
     */
    public function updateEntity(WindowProjectForm $windowProjectForm): bool
    {
        $model = self::findOne($windowProjectForm->id);
        if ($model === null) {
            throw new Exception('找不到这一条记录，不能更新');
        }
        try {
            $model->setAttributes($windowProjectForm->getAttributes());
            return $model->save();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry')){
                throw new Exception('已存在相同的产品：' . $windowProjectForm->product_name .
                    '、时间段：' . $windowProjectForm->period . '~' . ($windowProjectForm->period+1) . '、账号的数据：' .
                    $windowProjectForm->account_and_id . '，请检查好后再重新录入');
            }
            throw new Exception($e->getMessage());
        }

    }

    /**
     * 删除橱窗项目实体
     * @param WindowProjectDto $windowProjectDto
     * @return int
     * author: pengguochao
     * Date Time 2019/10/17 13:35
     */
    public function deleteEntity(WindowProjectDto $windowProjectDto): int
    {
        return self::deleteAll(['id'=>$windowProjectDto->id]);
    }
}