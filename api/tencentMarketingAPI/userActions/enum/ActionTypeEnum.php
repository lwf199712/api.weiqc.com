<?php

namespace app\api\tencentMarketingApi\userActions\enum;

/**
 * Interface ActionType
 *
 * @package app\api\tencentMarketingApi\userActions\enum
 * @author: lirong
 */
interface ActionTypeEnum
{
    /* @var string 自定义 */
    public const CUSTOM = 'CUSTOM';
    /* @var string 注册 */
    public const REGISTER = 'REGISTER';
    /* @var string 关键页面访问 */
    public const VIEW_CONTENT = 'VIEW_CONTENT';
    /* @var string 咨询(请在 action_param 中标识具体的咨询行为：action_param 中的 key 填写 consult_type，value 填写 ONLINE_CONSULT/MAKE_PHONE_CALL/RESERVE_PHONE_NUMBER 分别表示网页咨询/电话咨询/电话回拨); */
    public const CONSULT = 'CONSULT';
    /* @var string 加入购物车 */
    public const ADD_TO_CART = 'ADD_TO_CART';
    /* @var string 购买 */
    public const PURCHASE = 'PURCHASE';
    /* @var string 激活应用 */
    public const ACTIVATE_APP = 'ACTIVATE_APP';
    /* @var string 搜索 */
    public const SEARCH = 'SEARCH';
    /* @var string 收藏 */
    public const ADD_TO_WISHLIST = 'ADD_TO_WISHLIST';
    /* @var string 开始结算 */
    public const INITIATE_CHECKOUT = 'INITIATE_CHECKOUT';
    /* @var string 下单 */
    public const COMPLETE_ORDER = 'COMPLETE_ORDER';
    /* @var string 启动应用 */
    public const START_APP = 'START_APP';
    /* @var string 评分 */
    public const RATE = 'RATE';
    /* @var string 页面浏览，仅在 user_action_set 为站点时有效 */
    public const PAGE_VIEW = 'PAGE_VIEW';
    /* @var string 预约 */
    public const RESERVATION = 'RESERVATION';
    /* @var string 分享 */
    public const SHARE = 'SHARE';
    /* @var string 申请 */
    public const APPLY = 'APPLY';
    /* @var string 领取卡券(如 web 落地页领取优惠券等信息的行为) */
    public const CLAIM_OFFER = 'CLAIM_OFFER';
    /* @var string 导航(如点击导航按钮后打开地图页面的行为) */
    public const NAVIGATE = 'NAVIGATE';
    /* @var string 商品推荐(动态创意客户直接推送推荐结果时使用) */
    public const PRODUCT_RECOMMEND = 'PRODUCT_RECOMMEND';
    /* @var string 到店(线下行为，线下零售商接入数据时使用) */
    public const VISIT_STORE = 'VISIT_STORE';
    /* @var string 体验(线下行为，线下零售商接入数据时使用) */
    public const TRY_OUT = 'TRY_OUT';
    /* @var string 发货，订单发货 */
    public const DELIVER = 'DELIVER';
    /* @var string 签收，订单签收 */
    public const SIGN_IN = 'SIGN_IN';
}
