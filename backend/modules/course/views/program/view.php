<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model common\models\Program */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="card">

	<div class ="card-body">

    

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->program_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->program_id], [
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
            //'program_id',
            'title:ntext',
            'company.name',
            'description:ntext',
        ],
    ]) ?>
	<h3><?= 'Courses ' ?><?= Html::a('Add new', ['module/create'], ['class' => 'btn btn-default']) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'module_id',
            'program.title',
            'title',
            //'short_description:ntext',
            //'featured_video_url:ntext',
            // 'detailed_description:ntext',
            // 'status',
			[
			'class' => 'yii\grid\ActionColumn',
			'controller' => 'unit',
			'template' => '{units}', 
				'buttons' => [
					'units' => function ($url, $model, $key) {
						return Html::a('Units', ['unit/create', 'm_id'=>$model->module_id]);
					},
				]
			],
			
            ['class' => 'yii\grid\ActionColumn',
			'template' => '{update}{delete}',
			'controller' => 'module'],
        ],
    ]); ?>
	</div>
</div>
