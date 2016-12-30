<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchResetSchedule */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reset Schedules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reset-schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'label' => 'Module',
				'attribute' => 'module',
				'value' => function($data){
					return $data->unit->module->title;
				},
				'format' => 'html'
			],
			[
				'label' => 'Lesson',
				'attribute' => 'unit_id',
				'value' => function($data){
					return Html::a($data->unit->title,['unit/update?id='.$data->unit_id]);
				},
				'format' => 'html'
			],
            //'cron_time',
            //'actual_time',
			[
				'label' => 'Reset Scheduled At',
				'value' => function($data){
					return date('d-M-y', $data->actual_time)." 1.00 AM";
				}
			],
            'updated_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
