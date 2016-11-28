<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->company_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->company_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'about_us:ntext',
			[
                'attribute'=>'Logo',
				'value'=>Yii::$app->homeUrl.$model->logo,
				'format' => ['image',['width'=>'150px','height'=>'150px']],
				
            ],	
				'companyAdmin.email',
				
			[
                'attribute'=>'slug',
				//'value'=>Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['asdasd']),						
				'value'=>Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/login','companyslug' => $model->slug]),						
            ],	
				
        ],
    ]) ?>

</div>
