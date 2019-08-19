<?php
declare(strict_types=1);

/* @var $this yii\web\View */
/**@var $tmallOrderImport TmallOrderImport */

/** @var string $access_token */

use app\modules\v2\advertDept\domain\dto\TmallOrderImport;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;


$this->title                   = '天猫订单导入';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
                        'id'                   => 'form-id',
                        'options'              => ['enctype' => 'multipart/form-data'],
                        'action'               => Url::to('upload'),
                        'method'               => 'POST',
                        ])?>

<?= $form->field($tmallOrderImport, 'excelFile')->label('请选择订单表格')->fileInput() ?>

<script src="http://libs.baidu.com/jquery/2.0.0/jquery.js"></script>
<div class="form-group">
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary block']) ?>
</div>
<?php ActiveForm::end() ?>
