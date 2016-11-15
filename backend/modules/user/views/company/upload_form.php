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
	
	
	
		


    <?php $form = ActiveForm::begin( ['options' => ['enctype'=>'multipart/form-data'] ]); ?>
	
	   <?=$form->errorSummary($model);?>
	
	 <?= $form->field($model, 'upfile')->fileInput(['class'=>'form-control'])->label("Select Import Excel File Only");	?> 
		
    <div class="form-group">
        <?= Html::submitButton('Import',['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>



