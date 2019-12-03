<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StatisticsUrlGroupChannelDo;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelForm;
use Yii;
use app\models\User;
use yii\db\Exception;

/**
 * Class StatisticsUrlGroupChannelEntity
 * @package app\modules\v2\link\domain\entity
 */
class StatisticsUrlGroupChannelEntity extends StatisticsUrlGroupChannelDo
{
    /**
     * 创建渠道实体
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @author: qzr
     */
    public function createEntity(StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm): bool
    {
        $model = new self;
        $model->setAttributes($statisticsUrlGroupChannelForm->getAttributes());
        $nickname = User::findOne(['id' => Yii::$app->user->getId()])->username;
        $model->updater = $nickname;
        $model->update_time = time();
        $model->create_time = time();
        $model->is_delete = 0;
        $model->creator = $nickname;
        return $model->save();

    }

    /**
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @throws Exception
     * @author: qzr
     */
    public function updateEntity(StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm): bool
    {
        $model = self::findOne($statisticsUrlGroupChannelForm->id);
        if ($model === null) {
            throw new Exception('找不到要修改的内容');
        }
        $nickname = User::findOne(['id' => Yii::$app->user->getId()])->username;
        $model->updater = $nickname;
        $model->update_time = time();
        $model->channel_name = $statisticsUrlGroupChannelForm->channel_name;
        return $model->save();
    }

    /**
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @throws Exception
     * @author: qzr
     */
    public function deleteEntity(StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm): bool
    {
        $model = self::findOne($statisticsUrlGroupChannelForm->id);
        if ($model === null) {
            throw new Exception('找不到删除内容');
        }
        $nickname = User::findOne(['id' => Yii::$app->user->getId()])->username;
        $model->updater = $nickname;
        $model->update_time = time();
        $model->is_delete = 1;
        return $model->save();
    }


}
