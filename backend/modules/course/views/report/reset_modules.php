<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lessons';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>
<div class="card">

	<div class ="card-body">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?=Html::beginForm(['report/reset-modules','p_id'=>$p_id],'post');?>
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
            'title:ntext',
			[
				'label' => 'Reset Units',
				'format' => 'raw',
				'value' => function ($data) {
                        return Html::a('Reset Units',['reset-units','m_id'=>$data->module_id]);
                },
			],
          //  'company_id',
/* 			[
				'attribute' => 'company_id',
				'value' => 'company.name',
				'filter' => Html::activeDropDownList($searchModel, 'company_id', ArrayHelper::map(Company::find()->asArray()->all(), 'company_id', 'name'),['class'=>'form-control input-sm','prompt' => 'Company']),
			], */
            //'description:ntext',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	<?= Html::endForm();?> 
	</div>
</div>
