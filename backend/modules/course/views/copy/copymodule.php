<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Module */

$this->title = "Copy Module";
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-view">

    <h1><?= Html::encode($this->title) ?></h1>

		<?php 
$sessioncheck = Yii::$app->session->getFlash('Success');
if(isset($sessioncheck) && !empty($sessioncheck)) { ?>
<div id="w3-success-0" class="alert-success alert fade in">
<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
<?= Yii::$app->session->getFlash('Success'); ?>
</div>
<?php } ?>

	<div class="card">
	<div class="card-body">
	
		<?php $form = ActiveForm::begin(); ?>
			<div class="row">
				<div class="col-md-6">								
				   <?php
					$data = ArrayHelper::map($program, 'program_id', 'title');
					echo $form->field($model, 'program_id')->dropDownList(
					$data,           // Flat array ('id'=>'label')
					['prompt'=>'--Select Program--']    // options
					)->label("From Program"); ?>		 
				</div>
				
				<div class="col-md-6">
						<?php
					$data = ArrayHelper::map($program, 'program_id', 'title');
					echo $form->field($model, 'copy_program')->dropDownList(
					$data,           // Flat array ('id'=>'label')
					['prompt'=>'--Select Program--']   // options
					)->label("To Program"); ?>
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">				
					<?php
					echo $form->field($model, 'copy_module')->dropDownList(				
					['prompt'=>'--Select Module--' ]    // options
					)->label("Module to Copy"); ?>
					
				</div>
			</div>
				
		<div class="form-group">
			<?= Html::submitButton('Copy', ['class' =>'btn btn-success']) ?>
		</div>
		
		<?php ActiveForm::end(); ?>
	</div>
</div>

</div>


<script type="text/javascript">
$(document).ready(function(){
    $("#module-program_id").change(function(){
		var program_id = $(this).val();
		 $.ajax({
				   url: '<?=Url::to(['copy/get-modules'])?>',
				   type: 'POST',
				   data: {  program_id: program_id,
				   },
				   success: function(data) {		
						$("#module-copy_module").html(data);						
				   }
				 }); 
		
    });
	
	<?php if(isset($model->copy_module)){ ?>
		 $.ajax({
				   url: '<?=Url::to(['copy/get-modules-selected'])?>',
				   type: 'POST',
				   data: {  program_id: <?= $model->program_id ?>,
							module_id: <?= $model->copy_module ?>,
				   },
				   success: function(data) {		
						$("#module-copy_module").html(data);						
				   }
				 }); 
	<?php } ?>
});
</script>
