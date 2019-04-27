<?php

namespace app\daemon\course\conversion\controller;

use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;
use app\daemon\course\conversion\service\CourseStaticHitsService;
use Yii;
use yii\db\Exception;

/**
 * Class ConversionCommandsTest
 *
 * @property RedisUtils $redisUtils
 * @property ArrayUtils $arrayUtils
 * @property CourseStaticHitsService $commandsStaticHitsService
 *
 * @package app\commands
 * @author: lirong
 */
class ConversionController
{
    /* @var RedisUtils */
    protected $redisUtils;
    /* @var ArrayUtils */
    protected $arrayUtils;
    /* @var CourseStaticHitsService */
    protected $commandsStaticHitsService;

    /**
     * ConversionCommandsTest constructor.
     *
     * @param RedisUtils $redisUtils
     * @param ArrayUtils $arrayUtils
     * @param CourseStaticHitsService $commandsStaticHitsService
     */
    public function __construct(RedisUtils $redisUtils, ArrayUtils $arrayUtils, CourseStaticHitsService $commandsStaticHitsService)
    {
        $this->redisUtils = $redisUtils;
        $this->arrayUtils = $arrayUtils;
        $this->commandsStaticHitsService = $commandsStaticHitsService;
    }

    /**
     * Landing page conversions - add views
     *
     * @param array $redisAddViewDtoList
     * @return array
     * @throws Exception
     * @author: lirong
     */
    public function actionAddViews(array $redisAddViewDtoList): array
    {
        Yii::$app->db->beginTransaction();
        try {
            //获得对象组
            $redisAddViewBaseDto = new RedisAddViewDto();
            foreach ($redisAddViewDtoList as &$redisAddViewPop) {
                $redisAddViewDto = clone $redisAddViewBaseDto;
                $redisAddViewDto->attributes = json_decode($redisAddViewPop, true);
                $redisAddViewPop = $redisAddViewDto;
            }
            unset($redisAddViewPop);
            //去重
            $redisAddViewDtoList = $this->arrayUtils->uniqueArrayDelete($redisAddViewDtoList, ['ip', 'date', 'u_id']);
            //批量插入
            $falseUserActionsDtoList = $this->commandsStaticHitsService->batchInsert($redisAddViewDtoList);
            Yii::$app->db->beginTransaction()->commit();
            return $falseUserActionsDtoList;
        } catch (Exception $e) {
            Yii::$app->db->beginTransaction()->rollBack();
            throw new Exception($e->getMessage(), [], $e->getCode());
        }
    }

}
