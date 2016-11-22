<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Company;


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<h1>Import File</h1>
<div class="card">
	<div class="card-body">
	
	<?php 
$sessioncheck = Yii::$app->session->getFlash('Success');
if(isset($sessioncheck) && !empty($sessioncheck)) { ?>
<div id="w3-success-0" class="alert-success alert fade in">
<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
<?= Yii::$app->session->getFlash('Success'); ?>
</div>
<?php } ?>

<?php 
$sessioncheck = Yii::$app->session->getFlash('Error');
if(isset($sessioncheck) && !empty($sessioncheck)) { ?>
<div id="w3-danger-0" class="alert-danger alert fade in">
<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
<?= Yii::$app->session->getFlash('Error'); ?>
<?php 
	echo "<br>"; 
	$errordata = Yii::$app->session->getFlash('Error-data'); 
	print implode(",<br> ", $errordata); 	
	?>
</div>
<?php } ?>

	
    <?php $form = ActiveForm::begin( ['options' => ['enctype'=>'multipart/form-data'] ]); ?>
	
	<?= $form->errorSummary($model); ?>
	
	 <?= $form->field($model, 'upfile')->fileInput(['class'=>'form-control'])->label("Select Import Excel File Only");	?> 
		
    <div class="form-group">
        <?= Html::submitButton('Import',['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>



