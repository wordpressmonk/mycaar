<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Usercreate */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Usercreates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usercreate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
