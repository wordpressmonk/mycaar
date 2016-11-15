<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\MyCaar;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="card">

    <div class="card-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create-user'], ['class' => 'btn btn-success']) ?>
		
		 <?= Html::a('Import User', ['importexcel'], ['class' => 'btn btn-info','style'=>'margin-left:76%']) ?>
    </p>
	
	
	  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'username',
			'email:email',			
		
			[
  'class' => 'yii\grid\ActionColumn',
  'template' => '{view}{update}{delete}',
  'buttons' => [
    'view' => function ($url, $model) {
        return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-eye-open"></span>', 'view-user?id='.$model->id, [
                    'title' => Yii::t('app', 'View'),
        ]);
    },
	'update' => function ($url, $model) {
        return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-pencil"></span>', 'update-user?id='.$model->id, [
                    'title' => Yii::t('app', 'Update'),
        ]);
    },
	'delete' => function ($url, $model) {
        return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-trash"></span>', 'delete-user?id='.$model->id, [
                    'title' => Yii::t('app', 'Delete'),
        ]);
    },
  ],
],
        ],
    ]); ?>
	</div>
</div>

