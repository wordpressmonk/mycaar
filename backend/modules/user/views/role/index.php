<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchRole */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>


    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="card">
	<div class="card-body">
	
    <p>
        <?= Html::a('Create Role', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute' => 'company_id',
				'value' => 'company.name',
				 'filter' => Html::activeDropDownList($searchModel, 'company_id', ArrayHelper::map(Company::find()->all(), 'company_id', 'name'),['class'=>'form-control input-sm','prompt' => 'Company Name']), 
				 'visible' => Yii::$app->user->can('company manage'),
			],
			
            'title',
            'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	
	</div>
</div>
