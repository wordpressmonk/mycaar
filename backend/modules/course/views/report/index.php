<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use common\models\Role;
use common\models\Division;
use common\models\Location;
use common\models\State;
use common\models\MyCaar;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchUnit */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assessor Reports';
$this->params['breadcrumbs'][] = $this->title;


$accesslevel = isset($params['accesslevel'])?$params['accesslevel']:'';
$selected_division = isset($params['division'])?$params['division']:'';
$selected_role = isset($params['role'])?$params['role']:'';
$selected_location = isset($params['location'])?$params['location']:'';
$selected_state = isset($params['state'])?$params['state']:''; 

?>
<div class="unit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<div class="card card-collapse">
			<div class="card-head style-default">
				<div class="tools">
					<div class="btn-group">
						<a class="btn btn-icon-toggle btn-collapse" data-toggle="collapsed"><i class="fa fa-angle-down"></i></a>
					</div>
				</div>
				<header>Search</header>
			</div><!--end .card-head -->
			<div class="card-body" style="display:none" >
				<div class="program-search">
					<form method="get" >
						
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Role</label>

									<?= Html::dropDownList('role', "$selected_role",ArrayHelper::map(Role::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->orderBy('title')->all(), 'role_id', 'title'),['prompt'=>'--Select--','class'=>'form-control']) ?>

									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Division</label>

									<?= Html::dropDownList('division', "$selected_division",ArrayHelper::map(Division::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->orderBy('title')->all(), 'division_id', 'title'),['prompt'=>'--Select--','class'=>'form-control']) ?>

									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Location</label>

									<?= Html::dropDownList('location', "$selected_location",ArrayHelper::map(Location::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->orderBy('name')->all(), 'location_id', 'name'),['prompt'=>'--Select--','class'=>'form-control']) ?>

									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">State</label>

									<?= Html::dropDownList('state', "$selected_state",ArrayHelper::map(State::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->orderBy('name')->all(), 'state_id', 'name'),['prompt'=>'--Select--','class'=>'form-control']) ?>

									<div class="help-block"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Access Level [ student ]</label>
									
									<?= Html::dropDownList('accesslevel', "$accesslevel",MyCaar::getChildRoles('company_admin'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									
									<div class="help-block"></div>
								</div>
							</div>
						</div>	
						<div class="form-group">
							<button type="submit" value="search" name="clickaction" class="btn btn-primary searchclick">Search</button>  
							<a class="btn btn-danger" href="<?php echo Url::to(['report/assessor-report'])?>" >Clear Search </a>
						</div>
					</form>
				</div>
			</div><!--end .card-body -->
		</div><!--end .card -->
	
	

	
    <p>
        <?= Html::a('Dashboard', ['report/search#db'], ['class' => 'btn btn-success']) ?>
    </p>

	<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

				//'unit_id',
				//'unit.title',
				
				[
					'label' => 'Assessor',
					'attribute' => 'cap_done_by',
					'value' => 'assessor.fullname',
				
				],
				
				[
					'label' => 'Student',
					'attribute' => 'student_id',
					'value' => 'student.fullname',
				
				],
				
				[
					'label' => 'Module',
					'attribute' => 'module_id',
					'value' => 'unit.module.title',
				
				],
				[
					'label' => 'Lesson',
					'attribute' => 'unit_id',
					'value' => 'unit.title',
				
				],
				[
					'attribute' => 'updated_at',
					'value' => 'updated_at',
					'filter' => '<input placeholder="--Search--" type="text" name="SearchUnitReport[updated_at]" id="updated_at" class="form-control" />',
					'format' => 'html',
				],
				//'assessor.fullname',
				
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
</div>

 <script>
 //$(document).ready(function(){
	  	$('.card-head .tools .btn-collapse').on('click', function (e) {
			var card = $(e.currentTarget).closest('.card');
			materialadmin.AppCard.toggleCardCollapse(card);
		}); 

	$('#updated_at').datepicker({
	//$(document).find('#updated_at').datepicker({
		autoclose: true, 
		todayHighlight: true,
		format: 'yyyy-mm-dd',
		clearBtn: true
	});		
 //});		
</script>	