<?php


use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\course\urlConvert\domain\dto\RedisUrlConvertDto;
use app\models\dataObject\StaticClientDo;
use app\models\dataObject\StaticHitsDo;
use app\models\dataObject\StaticVisitDo;
use yii\db\Exception;

/**
 * Class UrlConvertController
 * @property RedisUtils $redisUtils
 * @property ArrayUtils $arrayUtils
 * @property CommandUrlConvertService $commandUrlConvertService
 * @property StaticClientDo $staticClientDo
 * @property StaticVisitDo $staticVisitDo
 * @property StaticHitsDo $staticHitsDo
 */
class UrlConvertController
{
    /* @var RedisUtils */
    protected $redisUtils;
    /* @var ArrayUtils */
    protected $arrayUtils;
    /** @var CommandUrlConvertService */
    protected $commandUrlConvertService;
    /** @var StaticClientDo */
    protected $staticClientDo;
    /** @var StaticVisitDo */
    protected $staticVisitDo;
    /** @var StaticHitsDo */
    protected $staticHitsDo;

    /**
     * ConversionCommandsTest constructor.
     *
     * UrlConvertController constructor.
     * @param RedisUtils $redisUtils
     * @param ArrayUtils $arrayUtils
     * @param CommandUrlConvertService $commandUrlConvertService
     * @param StaticClientDo $staticClientDo
     * @param StaticVisitDo $staticVisitDo
     * @param StaticHitsDo $staticHitsDo
     */
    public function __construct(RedisUtils $redisUtils,
                                ArrayUtils $arrayUtils,
                                CommandUrlConvertService $commandUrlConvertService,
                                StaticClientDo $staticClientDo,
                                StaticVisitDo $staticVisitDo,
                                StaticHitsDo $staticHitsDo)
    {
        $this->redisUtils     = $redisUtils;
        $this->arrayUtils     = $arrayUtils;

        $this->staticClientDo = $staticClientDo;
        $this->staticVisitDo  = $staticVisitDo;
        $this->staticHitsDo   = $staticHitsDo;

        $this->commandUrlConvertService = $commandUrlConvertService;
    }

    /**
     * @param array $redisAddUrlConvertHitsDtoList
     * @return array
     * @throws Exception
     */
    public function addUrConvertHits(array $redisAddUrlConvertHitsDtoList): array
    {
        Yii::$app->db->beginTransaction();
        try {
            //获得对象组
            $redisUrlConvertBaseDto = new RedisUrlConvertDto();
            foreach ($redisAddUrlConvertHitsDtoList as &$redisAddUrlConvertHitsPop) {
                $redisUrlConvertDto             = clone $redisUrlConvertBaseDto;
                $redisUrlConvertDto->attributes = json_decode($redisAddUrlConvertHitsPop, true);
                $redisAddUrlConvertHitsPop      = $redisUrlConvertDto;
            }
            unset($redisAddUrlConvertHitsPop);
            $redisAddUrlConvertHitsDtoList = $this->arrayUtils->uniqueArrayDelete($redisAddUrlConvertHitsDtoList, ['ip', 'date', 'u_id']);
            //批量插入
            $falseUserActionsDtoList = $this->commandUrlConvertService->batchInsert($redisAddUrlConvertHitsDtoList,$this->staticHitsDo::tableName());
            Yii::$app->db->beginTransaction()->commit();
            return $falseUserActionsDtoList;
        } catch (Exception $e) {
            Yii::$app->db->beginTransaction()->rollBack();
            throw new Exception($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * @param array $redisAddUrlConvertClientDtoList
     * @return array
     * @throws Exception
     */
    public function addUserConvertClient(array $redisAddUrlConvertClientDtoList): array
    {
        Yii::$app->db->beginTransaction();
        try {
            //获得对象组
            $redisUrlConvertBaseDto = new RedisUrlConvertDto();
            foreach ($redisAddUrlConvertClientDtoList as &$redisAddUrlConvertClientPop) {
                $redisUrlConvertDto             = clone $redisUrlConvertBaseDto;
                $redisUrlConvertDto->attributes = json_decode($redisAddUrlConvertClientPop, true);
                $redisAddUrlConvertClientPop    = $redisUrlConvertDto;
            }
            unset($redisAddUrlConvertClientPop);
            //批量插入
            $falseUserActionsDtoList = $this->commandUrlConvertService->batchInsert($redisAddUrlConvertClientDtoList,$this->staticClientDo::tableName());
            Yii::$app->db->beginTransaction()->commit();
            return $falseUserActionsDtoList;
        } catch (Exception $e) {
            Yii::$app->db->beginTransaction()->rollBack();
            throw new Exception($e->getMessage(), [], $e->getCode());
        }
    }


    /**
     * @param array $redisAddUrlConvertVisitDtoList
     * @return array
     * @throws Exception
     */
    public function addUserConvertVisit(array $redisAddUrlConvertVisitDtoList): array
    {
        Yii::$app->db->beginTransaction();
        try {
            //获得对象组
            $redisUrlConvertBaseDto = new RedisUrlConvertDto();
            foreach ($redisAddUrlConvertVisitDtoList as &$redisAddUrlConvertVisitPop) {
                $redisUrlConvertDto             = clone $redisUrlConvertBaseDto;
                $redisUrlConvertDto->attributes = json_decode($redisAddUrlConvertVisitPop, true);
                $redisAddUrlConvertVisitPop     = $redisUrlConvertDto;
            }
            unset($redisAddUrlConvertVisitPop);
            //批量插入
            $falseUserActionsDtoList = $this->commandUrlConvertService->batchInsert($redisAddUrlConvertVisitDtoList,$this->staticVisitDo::tableName());
            Yii::$app->db->beginTransaction()->commit();
            return $falseUserActionsDtoList;
        } catch (Exception $e) {
            Yii::$app->db->beginTransaction()->rollBack();
            throw new Exception($e->getMessage(), [], $e->getCode());
        }
    }
}