<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


	
	
	
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
 
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <!--<?= $form->field($model, 'role')->textInput() ?>-->
	
	<?php if(isset($model->c_id)){
			   $cmpid = $model->c_id;
	} else {   $cmpid = Yii::$app->user->identity->c_id; }
	?>
    <?= $form->field($model, 'c_id')->hiddenInput(['value' =>$cmpid ])->label(false); ?>

<?php 
	if(isset(Yii::$app->user->identity->role) && (Yii::$app->user->identity->role == 3))
		$roles = array( "User","Accessor", "Company Admin","Super Admin"); 
	else if(isset(Yii::$app->user->identity->role) && (Yii::$app->user->identity->role == 2))
		$roles = array( "User","Accessor"); 
	
	?>
	

		 <div class="form-group field-user-role required">
<label class="control-label" for="user-role">Role</label>
<select type="text" id="user-role" class="form-control" name="User[role]">
		<?php if(isset($roles) && !empty($roles))
		{
			foreach($roles as $key=>$tmp)
			{
				if((isset($model->role)) && ($model->role == $key))
				{
					echo "<option selected='selected' value=".$key.">".$tmp."</option>";
				} else {
					echo "<option value=".$key.">".$tmp."</option>";
				}
			}
		}			?>
<select>
<p class="help-block help-block-error"></p>
</div>

   
   

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
