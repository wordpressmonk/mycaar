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
<!-- BEGIN MODALS -->
<div class="row">
    
    <div class="col-lg-offset-1 col-md-8">
        <div class="card">
            <div class="card-body text-center">
                
                <button class="btn btn-default-bright btn-raised" data-toggle="modal" data-target="#formModal">Form modal</button>
                
            </div><!--end .card-body -->
        </div><!--end .card -->
        <em class="text-caption">Click to see the modals</em>
    </div><!--end .col -->
</div><!--end .row -->
<!-- END MODALS -->


<!-- BEGIN FORM MODAL MARKUP -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="formModalLabel">Login to continue</h4>
            </div>
            <form class="form-horizontal" role="form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="email1" class="control-label">Email</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="email" name="email1" id="email1" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="password1" class="control-label">Password</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="password" name="password1" id="password1" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="cb1"> Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END FORM MODAL MARKUP -->
