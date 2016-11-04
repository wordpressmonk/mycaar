<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SetPassword */
/* @var $form ActiveForm */
?>
<div class="site-setpassword">

    <?php $form = ActiveForm::begin(['action'=> 'pwdregister']); ?>

	 <?= $form->field($model, 'password_hash')->passwordInput(['autofocus' => true])->label('New Password') ?>
	 
	 <?= $form->field($model, 'userid')->hiddenInput(['value'=> $user_id ])->label(false) ?>
	 
	 
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-setpassword -->
