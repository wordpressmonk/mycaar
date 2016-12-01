<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

	 <div class="row">
		<div class="col-md-6">
			<?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
		</div>	
		<div class="col-md-6">
		  <?php if(\Yii::$app->user->can('company_admin')) { ?>
			<?= Html::a('Import User', ['importuser/importexcel'], ['class' => 'btn btn-info pull-right']) ?>
		  <?php } ?>
		</div>	 
</div>	 
  
	
	<div class="small-padding"></div>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             ['label' => 'Username / Email ID',
				'attribute' => 'email',			
			 ],
			
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	</div>
</div>
