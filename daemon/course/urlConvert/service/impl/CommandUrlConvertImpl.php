<?php declare(strict_types=1);


use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\common\utils\CommandsBatchInsertUtils;
use app\daemon\course\urlConvert\domain\dto\RedisUrlConvertDto;
use app\models\dataObject\StaticHitsDo;
use yii\db\Exception;

/**
 * Class CommandUrlConvertImpl
 * @property RedisUtils $redisUtils
 * @property ArrayUtils $arrayUtils
 * @property CommandsBatchInsertUtils $batchInsertUtils;
 */
class CommandUrlConvertImpl implements CommandUrlConvertService
{
    /** @var RedisUtils */
    protected $redisUtils;
    /** @var ArrayUtils */
    protected $arrayUtils;
    /** @var CommandsBatchInsertUtils */
    protected $batchInsertUtils;

    /**
     * CommandUrlConvertImpl constructor.
     * @param RedisUtils $redisUtils
     * @param ArrayUtils $arrayUtils
     * @param CommandsBatchInsertUtils $batchInsertUtils
     */
    public function __construct(
        RedisUtils $redisUtils,
        ArrayUtils $arrayUtils,
        CommandsBatchInsertUtils $batchInsertUtils)
    {
        $this->redisUtils       = $redisUtils;
        $this->arrayUtils       = $arrayUtils;
        $this->batchInsertUtils = $batchInsertUtils;


    }

    /**
     * @param array $redisAddUrlConvertDtoList
     * @param string $tableName
     * @return array
     * @throws Exception
     */
    public function batchInsert(array $redisAddUrlConvertDtoList,string $tableName): array
    {
        if ($redisAddUrlConvertDtoList) {
            $staticsUpdateTime = $this->redisUtils->getRedis()->get('statics_update_time'); //上一次更新时间

            foreach ($redisAddUrlConvertDtoList as $key => $redisAddUrlConvertDto) {
                /** @var RedisUrlConvertDto $redisAddUrlConvertDto */
                if ($redisAddUrlConvertDto->createtime < $staticsUpdateTime || $redisAddUrlConvertDto->createtime > time()) {
                    unset($redisAddUrlConvertDto[$key]);
                }
                /* @var $staticHitsFind StaticHitsDo */
                if ($this->arrayUtils->arrayExists($redisAddUrlConvertDto, [
                    'ip' => $redisAddUrlConvertDto->ip,
                    'date' => $redisAddUrlConvertDto->date,
                    'u_id' => $redisAddUrlConvertDto->u_id,
                ])) {
                    unset($redisAddUrlConvertDtoList[$key]);
                }
            }
            //批量插入
            $lastInsertId = $this->batchInsertUtils->onDuplicateKeyUpdate($redisAddUrlConvertDtoList, [
                'u_id',      //statis_url表id
                'ip',        //IP地址
                'country',   //国家
                'area',      //区域
                'date',      //日期
                'page',      //页
                'referer',   //引荐
                'agent',     //代理人
                'createtime',//创建时间
            ], $tableName);
            if (!$lastInsertId) {
                throw new Exception('批量插入失败!返回的id为空', [], 500);
            }


            $this->redisUtils->getRedis()->set('statics_update_time', time());
        }

        return [];
    }


}