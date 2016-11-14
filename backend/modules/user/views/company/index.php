<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\MyCaar;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchCompany */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="card">

    <div class="card-body">

    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',           
			[
				'attribute' => 'admin',
				'value' => 'companyAdmin.email',
				'filter' => Html::activeDropDownList($searchModel, 'admin', ArrayHelper::map(MyCaar::getUserAllByrole("company_admin"), 'id', 'email'),['class'=>'form-control input-sm','prompt' => 'Company Admin']),
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	</div>
</div>
