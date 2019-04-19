<?php

namespace app\modules\v1\oauth\enum;

/**
 * 接口常量
 * Class AuthorizeEnum
 *
 * @package app\modules\v1\oauth\enum
 * @author: lirong
 */
abstract class AuthorizeEnum
{
    /**
     * 广告投放
     *
     * @var string
     * @author lirong
     */
    public const ADS_MANAGEMENT = 'ads_management';
    /**
     * 数据洞察
     *
     * @var string
     * @author lirong
     */
    public const ADS_INSIGHTS = 'ads_insights';
    /**
     * 帐号服务
     *
     * @var string
     * @author lirong
     */
    public const ACCOUNT_MANAGEMENT = 'account_management';
    /**
     * 人群管理
     *
     * @var string
     * @author lirong
     */
    public const AUDIENCE_MANAGEMENT = 'audience_management';
    /**
     * 用户行为数据接入
     *
     * @var string
     * @author lirong
     */
    public const USER_ACTIONS = 'user_actions';
}