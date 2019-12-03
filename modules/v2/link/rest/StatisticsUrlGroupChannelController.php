<?php declare(strict_types=1);

namespace app\modules\v2\link\rest;

use app\common\rest\AdminBaseController;
use app\modules\v2\link\domain\entity\StatisticsUrlGroupChannelEntity;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelForm;
use app\modules\v2\link\domain\dto\StatisticsUrlGroupChannelQuery;
use app\modules\v2\link\domain\repository\StatisticsUrlGroupChannelDoManager;
use RuntimeException;
use Yii;
use Exception;
use yii\base\Model;

/**
 * Class StatisticsUrlGroupChannelController
 * @package app\modules\v2\link\rest
 */
class StatisticsUrlGroupChannelController extends AdminBaseController
{
    /** @var StatisticsUrlGroupChannelForm */
    public $statisticsUrlGroupChannelForm;
    /** @var StatisticsUrlGroupChannelQuery */
    public $statisticsUrlGroupChannelQuery;
    /** @var StatisticsUrlGroupChannelEntity */
    public $statisticsUrlGroupChannelEntity;
    /** @var StatisticsUrlGroupChannelDoManager */
    public $statisticsUrlGroupChannelDoManager;

    public function __construct($id, $module,
                                StatisticsUrlGroupChannelForm $statisticsUrlGroupChannelForm,
                                StatisticsUrlGroupChannelQuery $statisticsUrlGroupChannelQuery,
                                StatisticsUrlGroupChannelEntity $statisticsUrlGroupChannelEntity,
                                StatisticsUrlGroupChannelDoManager $statisticsUrlGroupChannelDoManager
        , $config = [])
    {
        $this->statisticsUrlGroupChannelForm = $statisticsUrlGroupChannelForm;
        $this->statisticsUrlGroupChannelQuery = $statisticsUrlGroupChannelQuery;
        $this->statisticsUrlGroupChannelEntity = $statisticsUrlGroupChannelEntity;
        $this->statisticsUrlGroupChannelDoManager = $statisticsUrlGroupChannelDoManager;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }


    /**
     * 实体转化
     * @param string $actionName
     * @return Model
     * @throws Exception
     * @author: qzr
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName) {
            case 'actionIndex':
                return $this->statisticsUrlGroupChannelQuery;
            case 'actionCreate':
                return $this->statisticsUrlGroupChannelForm;
            case 'actionUpdate':
                return $this->statisticsUrlGroupChannelForm;
            case 'actionDelete':
                return $this->statisticsUrlGroupChannelForm;
            default:
                throw new RuntimeException('UnKnow ActionName ');
        }
    }

    /**
     * 查询渠道详情
     * @return array
     * @author: qzr
     */
    public function actionIndex(): array
    {
        try {
            $list = $this->statisticsUrlGroupChannelDoManager->listDataProvider($this->statisticsUrlGroupChannelQuery)->getModels();
            $data['list'] = $list;
            $data['totalCount'] = $this->statisticsUrlGroupChannelDoManager->listDataProvider($this->statisticsUrlGroupChannelQuery)->getTotalCount();
            return ['成功返回数据', 200, $data];
        } catch (Exception $exception) {
            return ['查询失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 创建渠道
     * @return array
     * @author: qzr
     */
    public function actionCreate(): array
    {
        try {
            $data = [];
            $res = $this->statisticsUrlGroupChannelEntity->createEntity($this->statisticsUrlGroupChannelForm);
            if ($res === true) {
                $data['id'] = Yii::$app->db->getLastInsertID();
                $data['channel_name'] = $this->statisticsUrlGroupChannelForm->channel_name;
            }
            return ['新增成功', 200, $data];
        } catch (Exception $exception) {
            return ['新增失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 修改实体
     * @return array
     * @author: qzr
     */
    public function actionUpdate(): array
    {
        try {
            $res = $this->statisticsUrlGroupChannelEntity->updateEntity($this->statisticsUrlGroupChannelForm);

            if ($res === false) {
                throw new \yii\db\Exception('修改渠道失败');
            }
            $data['id'] = $this->statisticsUrlGroupChannelForm->id;
            $data['channel_name'] = $this->statisticsUrlGroupChannelForm->channel_name;
            return ['修改成功', 200, $data];
        } catch (Exception $exception) {
            return ['修改失败', 500, $exception->getMessage()];
        }
    }

    /**
     * 删除实体
     * @return array
     * @author: qzr
     */
    public function actionDelete(): array
    {
        try {
            $id = $this->statisticsUrlGroupChannelEntity->deleteEntity($this->statisticsUrlGroupChannelForm);
            if ($id === false) {
                throw new \yii\db\Exception('删除渠道失败');
            }
            $data['id'] = $this->statisticsUrlGroupChannelForm->id;
            return ['删除成功', 200, $data];
        } catch (Exception $exception) {
            return ['删除失败', 500, $exception->getMessage()];
        }
    }


}