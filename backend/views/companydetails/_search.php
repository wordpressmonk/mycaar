<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanyDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cmp_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'company_name') ?>

    <?= $form->field($model, 'company_url') ?>

    <?= $form->field($model, 'company_email') ?>

    <?php // echo $form->field($model, 'company_logo') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
