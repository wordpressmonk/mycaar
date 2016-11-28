<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\models\UserProfile;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programs';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>
	<div class="card">

		<div class ="card-body">


		<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
		<?=Html::beginForm(['report/reset-users','u_id'=>$u_id],'post');?>
		<p>
			<?=Html::submitButton('Reset Selected', ['class' => 'btn btn-info',]);?>
		</p>
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\CheckboxColumn'],
				//['class' => 'yii\grid\SerialColumn'],

			 //   'program_id',
				//'title:ntext',
				//'student.fullname',
				[
					'attribute' => 'student_id',
					'value' => 'student.fullname',
					'filter' => Html::activeDropDownList($searchModel, 'student_id', ArrayHelper::map(UserProfile::find()->joinWith(['user'])->where(['user.c_id'=>\Yii::$app->user->identity->c_id])->asArray()->all(), 'user_id', function($model, $defaultValue) {
							return $model['firstname'].'-'.$model['lastname'];
						}),['class'=>'form-control input-sm','prompt' => '--Search--']),
				],
				'unit_id',
				

			],
		]); ?>
		<?= Html::endForm();?> 
		</div>
	</div>
