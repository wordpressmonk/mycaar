<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchUnit */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assessor Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Dashboard', ['report/search#db'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    
	<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

				//'unit_id',
				//'unit.title',
				[
					'label' => 'Module',
					'attribute' => 'module_id',
					'value' => 'unit.module.title',
				
				],
				[
					'label' => 'Unit',
					'attribute' => 'unit_id',
					'value' => 'unit.title',
				
				],
				[
					'label' => 'Assessed By',
					'attribute' => 'cap_done_by',
					'value' => 'assessor.fullname',
				
				],
				//'assessor.fullname',
				[
					'label' => 'Student',
					'attribute' => 'student_id',
					'value' => 'student.fullname',
				
				],
				[
					'attribute' => 'capability_progress',
					'format' => 'raw',
					'value' => function($data){
						if($data->capability_progress== 100)
							$color = "green";
						else $color = "amber";
						//switch($color){
						//	case "amber":
								return "<div class='td-{$color}'>$color</div>";
						//}
						//return $data->capability_progress;
					}
				],
				//'capability_progress',
				

				//['class' => 'yii\grid\ActionColumn'],
			],
		]); ?>
<?php Pjax::end(); ?></div>