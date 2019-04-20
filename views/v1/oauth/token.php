<?php

/* @var $this yii\web\View */

/* @var $oauthDto OauthDto */

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use yii\helpers\Html;

$this->title = '您的绑定账号信息:';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div>请保管好如下信息,并交给开发人员</div>
    <div>绑定的推广帐号对应的QQ号 : <?= $oauthDto->authorizer_info->account_uin ?></div>
    <div>绑定的推广帐号 id : <?= $oauthDto->authorizer_info->account_id ?></div>
</div>
