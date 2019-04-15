<?php

namespace app\commands\conversionCommands\service\impl;

use app\commands\conversionCommands\po\CommandsStaticHitsPo;
use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\utils\BatchInsertUtils;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Interface ConversionService
 *
 * @property CommandsStaticHitsPo $staticHits
 * @property BatchInsertUtils $batchInsertUtils
 * @author: lirong
 */
class CommandsCommandsStaticHitsImpl extends BaseObject implements CommandsStaticHitsService
{
    /* @var CommandsStaticHitsPo */
    private $staticHits;
    /* @var BatchInsertUtils */
    private $batchInsertUtils;

    /**
     * StaticServiceConversionsImpl constructor.
     *
     * @param CommandsStaticHitsPo $staticHits
     * @param BatchInsertUtils $batchInsertUtils
     * @param array $config
     */
    public function __construct(CommandsStaticHitsPo $staticHits, BatchInsertUtils $batchInsertUtils, $config = [])
    {
        $this->staticHits = $staticHits;
        $this->batchInsertUtils = $batchInsertUtils;
        parent::__construct($config);
    }

    /**
     * batch insert
     *
     * @param array $staticHitsList
     * @return void
     * @throws Exception
     * @author: lirong
     */
    public function batchInsert(array $staticHitsList): void
    {
        if ($staticHitsList) {
            //数据库去重处理
            $staticHitsFindList = $this->staticHits::find()->select(['ip', 'date', 'u_id']);
            foreach ($staticHitsList as $staticHits) {
                /* @var $staticHits CommandsStaticHitsPo */
                $staticHitsFindList = $staticHitsFindList->orWhere([
                    'ip'   => $staticHits->ip,
                    'date' => $staticHits->date,
                    'u_id' => $staticHits->u_id,
                ]);
            }
            $staticHitsFindList = $staticHitsFindList->all();
            //TODO 去除重复

            //批量插入
            $this->batchInsertUtils->onDuplicateKeyUpdate($staticHitsFindList, [
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
        }
    }
}
