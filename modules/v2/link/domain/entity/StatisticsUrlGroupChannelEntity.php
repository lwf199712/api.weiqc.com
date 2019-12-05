<?php declare(strict_types=1);

namespace app\modules\v2\link\domain\entity;

use app\models\dataObject\StatisticsUrlGroupChannelDo;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelForm;
use yii\db\Exception;

/**
 * Class StatisticsUrlGroupChannelEntity
 * @package app\modules\v2\link\domain\entity
 * @author: qzr
 */
class StatisticsUrlGroupChannelEntity extends StatisticsUrlGroupChannelDo
{

    /**
     * @param StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm
     * @return bool
     * @author: qzr
     * Date: 2019/12/5
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
     * Date: 2019/12/5
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
     * Date: 2019/12/5
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

