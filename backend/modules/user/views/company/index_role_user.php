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

 <div class="row">
</div>	 
  
	
	<div class="small-padding"></div>
	  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'firstname',
				'value' => 'userProfile.firstname',				
			],	
			[
				'attribute' => 'lastname',
				'value' => 'userProfile.lastname',				
			],	
			
			[
				'attribute' => 'roleName',
				'value' => 'roleName',
			 	 'filter' => Html::activeDropDownList($searchModel, 'roleName',MyCaar::getChildRolesName(MyCaar::getRoleNameByUserid(Yii::$app->user->identity->id)),['class'=>'form-control input-sm','prompt' => 'Role Name']),   
				
			],
			
			
			
            ['label' => 'Username / Email ID',
				'attribute' => 'email',			
			 ],			
		
		
			[
  'class' => 'yii\grid\ActionColumn',
  'template' => '{view}',
  'buttons' => [
    'view' => function ($url, $model) {
        return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-eye-open"></span>', 'view-role-user?id='.$model->id, [
                    'title' => Yii::t('app', 'View'),
        ]);
    },	
  ],
],
        ],
    ]); ?>
	</div>
</div>



