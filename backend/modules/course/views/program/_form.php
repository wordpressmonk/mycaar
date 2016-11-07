<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Company;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\Program */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card">

	<div class ="card-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['rows' => 6,'class'=>'form-control']) ?>

	<?php
	if(\Yii::$app->user->identity->role == 'Superadmin'){
		$data = ArrayHelper::map(Company::find()->all(), 'c_id', 'name');
		echo $form->field($model, 'company_id')->widget(Select2::classname(), [
			'data' => $data,
			'options' => ['placeholder' => 'Select company'],
		]);		
	}
	?>
	
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>

