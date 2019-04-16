<?php

namespace app\commands\conversionCommands\service\impl;

use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\commands\conversionCommands\domain\dto\RedisAddViewDto;
use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\commands\utils\CommandsBatchInsertUtils;
use app\common\exception\TencentMarketingApiException;
use app\models\po\StaticHitsPo;
use app\utils\ArrayUtils;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Interface ConversionService
 *
 * @property StaticHitsPo $staticHits
 * @property CommandsBatchInsertUtils $batchInsertUtils
 * @property ArrayUtils $arrayUtils
 * @property UserActionsApi $userActionsApi
 * @author: lirong
 */
class CommandsCommandsStaticHitsImpl extends BaseObject implements CommandsStaticHitsService
{
    /* @var UserActionsApi */
    protected $userActionsApi;
    /* @var StaticHitsPo */
    private $staticHits;
    /* @var CommandsBatchInsertUtils */
    private $batchInsertUtils;
    /* @var ArrayUtils */
    private $arrayUtils;

    /**
     * UserActionUserActionStaticServiceConversionsImpl constructor.
     *
     * @param StaticHitsPo $staticHits
     * @param CommandsBatchInsertUtils $batchInsertUtils
     * @param ArrayUtils $arrayUtils
     * @param UserActionsApi $userActionsApi
     * @param array $config
     */
    public function __construct(StaticHitsPo $staticHits,
                                CommandsBatchInsertUtils $batchInsertUtils,
                                ArrayUtils $arrayUtils,
                                UserActionsApi $userActionsApi,
                                $config = [])
    {
        $this->staticHits = $staticHits;
        $this->batchInsertUtils = $batchInsertUtils;
        $this->arrayUtils = $arrayUtils;
        $this->userActionsApi = $userActionsApi;
        parent::__construct($config);
    }

    /**
     * batch insert
     *
     * @param array $redisAddViewDtoList
     * @return void
     * @throws Exception
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function batchInsert(array $redisAddViewDtoList): void
    {
        if ($redisAddViewDtoList) {
            //数据库去重处理
            $staticHitsFindList = $this->staticHits::find()->select(['ip', 'date', 'u_id']);
            foreach ($redisAddViewDtoList as $redisAddViewDto) {
                /* @var $redisAddViewDto RedisAddViewDto */
                $staticHitsFindList = $staticHitsFindList->orWhere([
                    'ip'   => $redisAddViewDto->ip,
                    'date' => $redisAddViewDto->date,
                    'u_id' => $redisAddViewDto->u_id,
                ]);
            }
            $staticHitsFindList = $staticHitsFindList->all();

            foreach ($redisAddViewDtoList as $key => $redisAddViewDto) {
                /* @var $staticHitsFind StaticHitsPo */
                $this->arrayUtils->arrayExists($staticHitsFindList, [
                    'ip'   => $redisAddViewDto->ip,
                    'date' => $redisAddViewDto->date,
                    'u_id' => $redisAddViewDto->u_id
                ]);
                unset($redisAddViewDtoList[$key]);
            }
            //批量插入
            $lastInsertId = $this->batchInsertUtils->onDuplicateKeyUpdate($redisAddViewDtoList, [
                'u_id',      //=> 'statis_url表id',
                'ip',        //=> 'IP地址',
                'country',   //=> '国家',
                'area',      //=> '区域',
                'date',      //=> '日期',
                'page',      //=> '页',
                'referer',   //=> '引荐',
                'agent',     //=> '代理人',
                'createtime',//=> '创建时间',
            ], $this->staticHits::tableName());
            //广点通用户行为点击数增加
            $this->userActionsApi->add($userActionsDto);
        }
    }

    /**
     * @param RedisAddViewDto $redisAddViewDto
     * @return mixed
     * @author: lirong
     */
    public function insert(RedisAddViewDto $redisAddViewDto): void
    {
        // TODO: Implement insert() method.
    }
}
