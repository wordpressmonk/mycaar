<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Usercreate */

$this->title = "View User";
$this->params['breadcrumbs'][] = ['label' => 'Usercreates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usercreate-view">

    <h1><?= Html::encode($model->email) ?></h1>

	
		<?php 
$sessioncheck = Yii::$app->session->getFlash('Success');

if(isset($sessioncheck) && !empty($sessioncheck)) { ?>
<div id="w3-success-0" class="alert-success alert fade in">
<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
<?= Yii::$app->session->getFlash('Success'); ?>
</div>
<?php } ?>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'username',
         /*    'auth_key',
            'password_hash',
            'password_reset_token', */
            'email:email',
            [
            'label'=>'role',
            'value'=>$model->rolename($model->role),
			
			],
            'c_id',
            'status',
           /*  'created_at',
            'updated_at', */
        ],
    ]) ?>

</div>
