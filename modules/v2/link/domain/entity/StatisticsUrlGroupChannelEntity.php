<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StatisticsUrlGroupChannelDo;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelForm;
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
        $this->setAttributes($statisticsUrlGroupChannelForm->getAttributes());
        return $this->save();
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
        $model->is_delete = 1;
        return $model->save();
    }
}

