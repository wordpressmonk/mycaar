<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Division;
use common\models\State;
use common\models\Location;
use common\models\Role;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

			 <?= $form->field($profile, 'firstname',['inputOptions' => ['autocomplete' => 'off']])->textInput(['autofocus' => true]) ?>
			 
			 <?= $form->field($profile, 'lastname')->textInput() ?>
			  
             <?= $form->field($model, 'email')->textInput()->label("Username / Email ID") ?>
			
			 <?= $form->field($model, 'company_id')->hiddenInput(['value'=>$company_id])->label(false)  ?>
			
			<?php
				$role = ArrayHelper::map(Role::find()->where(['company_id' =>$company_id])->all(), 'role_id', 'title');
					echo $form->field($profile, 'role')->dropDownList(
					$role,           // Flat array ('id'=>'label')
					['prompt'=>'--Role--']    // options
				); 
			?>

             <?php
				$division = ArrayHelper::map(Division::find()->where(['company_id' =>$company_id])->all(), 'division_id', 'title');
					echo $form->field($profile, 'division')->dropDownList(
					$division,           // Flat array ('id'=>'label')
					['prompt'=>'--Division--']    // options
				); 
			?>
			
			<?php
				$location = ArrayHelper::map(Location::find()->where(['company_id' =>$company_id])->all(), 'location_id', 'name');
					echo $form->field($profile, 'location')->dropDownList(
					$location,           // Flat array ('id'=>'label')
					['prompt'=>'--Location--']    // options
					); 
			?>
		
			<?php
				$state = ArrayHelper::map(State::find()->where(['company_id' =>$company_id])->all(), 'state_id', 'name');
					echo $form->field($profile, 'state')->dropDownList(
					$state,           // Flat array ('id'=>'label')
					['prompt'=>'--State--']    // options
				);  
			?>
			
                <?= $form->field($model, 'password')->passwordInput() ?>
				
                <?= $form->field($model, 'confirm_password')->passwordInput()?>
				
                <div class="form-group">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
