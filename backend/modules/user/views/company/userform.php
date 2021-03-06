<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Company;
use common\models\Division;
use common\models\State;
use common\models\Location;
use common\models\Role;
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="card">
	<div class="card-body">

		<?php $form = ActiveForm::begin(); ?>

		<div class="col-md-6 col-sm-6">
		
		<?= $form->field($profile, 'firstname')->textInput(['maxlength' => true])->label("Firstname *") ?>
		
		<?= $form->field($profile, 'lastname')->textInput(['maxlength' => true])->label("Lastname *") ?>
		
		<?php if($model->isNewRecord){ ?>
		<?= $form->field($model, 'email')->textInput(['maxlength' => true])->label("Username / Email ID *") ?>
		<?php } else { ?>
		<?= $form->field($model, 'email')->textInput(['maxlength' => true,'readonly'=>'readonly'])->label("Username / Email ID *") ?>
		<?php } ?>
		
		<?= $form->field($model, 'password')->passwordInput()->label("Password (Optional)") ?>
		
		
			<?= $form->field($model, 'role')->dropDownList(
            $roles,           // Flat array ('id'=>'label')
            ['prompt'=>'--Access Level--']    // options
        )->label("User Access Level"); ?>
		
	</div>
	<div class="col-md-6 col-sm-6">
		<?= $form->field($profile, 'employee_number')->textInput(['maxlength' => true]) ?>
	
		<?php
		$division = ArrayHelper::map(Division::find()->where(['company_id' =>Yii::$app->user->identity->c_id])->orderBy('title')->all(), 'division_id', 'title');
			echo $form->field($profile, 'division')->dropDownList(
            $division,           // Flat array ('id'=>'label')
            ['prompt'=>'--Division--']    // options
        );  ?>
		
		
		<?php
		$state = ArrayHelper::map(State::find()->where(['company_id' =>Yii::$app->user->identity->c_id])->orderBy('name')->all(), 'state_id', 'name');
			echo $form->field($profile, 'state')->dropDownList(
            $state,           // Flat array ('id'=>'label')
            ['prompt'=>'--State--']    // options
        );  ?>
		
		
		<?php
		$location = ArrayHelper::map(Location::find()->where(['company_id' =>Yii::$app->user->identity->c_id])->orderBy('name')->all(), 'location_id', 'name');
			echo $form->field($profile, 'location')->dropDownList(
            $location,           // Flat array ('id'=>'label')
            ['prompt'=>'--Location--']    // options
        );  ?>
		
		<?php
		$role = ArrayHelper::map(Role::find()->where(['company_id' =>Yii::$app->user->identity->c_id])->orderBy('title')->all(), 'role_id', 'title');
			echo $form->field($profile, 'role')->dropDownList(
            $role,           // Flat array ('id'=>'label')
            ['prompt'=>'--Role--']    // options
        );  ?>
	</div>	
	<div class="col-md-12 col-sm-12">
		<div class="form-group" align="center" >
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	</div>

		
		<?php ActiveForm::end(); ?>
	</div>
</div>
