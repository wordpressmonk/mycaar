<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\MyCaar;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card">
	<div class="card-body">

    <?php $form = ActiveForm::begin( ['options' => ['enctype'=>'multipart/form-data'] ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'about_us')->textarea(['rows' => 3]) ?>
	
	<?php if($model->logo != '' ){ ?>
		<div class="form-group field-company-logo">
			<label class="control-label" >Logo</label>
				<img src="<?=Yii::$app->homeUrl.$model->logo ?>" width="150px" height="150px"/>
				<a id="<?= $model->company_id ?>" class="removelogo" >Remove</a>
		</div>		
	<?php } else {
					echo $form->field($model, 'logo')->fileInput(['class'=>'form-control']);
			} 
	
		if(\Yii::$app->user->can('company manage')) 
		{
			$data = ArrayHelper::map(MyCaar::getUserAllByrole("company_admin"), 'id', 'email');			
			echo $form->field($model, 'admin')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Select Company Admin','newOption'=>true],
			]);	
			
		}  ?> 
		
		
		
		<?= $form->field($model, 'slug')->textInput(['maxlength' => 100]) ?>
		
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>

<script>
 $(document).ready(function () {
	 
		  $(".removelogo").click(function() {
			 var company_id = $(this).attr('id');
				
				$.ajax({
				   url: '<?=Yii::$app->homeUrl."user/company/removelogo"?>',
				   type: 'POST',
				   data: {  company_id:company_id
				   },
				   success: function(data) {
							 location.reload();
				   }

				});

		  });
		 $("#company-name").change(function() {
				var slugval1 = $(this).val();
				var	slugval2 = toCamelCase(slugval1);
				var slugval3 = slugify(slugval2);	
				$("#company-slug").val(slugval3);				
		 });		 
		  
		function toCamelCase(str) {
			  // Lower cases the string
			  return str.toLowerCase()
				// Replaces any - or _ characters with a space 
				.replace( /[-_]+/g, ' ')
				// Removes any non alphanumeric characters 
				.replace( /[^\w\s]/g, '')
				// Uppercases the first character in each group immediately following a space 
				// (delimited by spaces) 
				.replace( / (.)/g, function($1) { return $1.toUpperCase(); })
			   
			}

		function slugify(text){
			  return text.toString()
				.replace(/\s+/g, '-')           // Replace spaces with -
				.replace(/[^\u0100-\uFFFF\w\-]/g,'-') // Remove all non-word chars ( fix for UTF-8 chars )
				.replace(/\-\-+/g, '-')         // Replace multiple - with single -
				.replace(/^-+/, '')             // Trim - from start of text
				.replace(/-+$/, '');            // Trim - from end of text
		}

	
 });
</script>

