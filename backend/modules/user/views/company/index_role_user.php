<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\MyCaar;

use yii\helpers\Url;
use common\models\Role;
use common\models\Division;
use common\models\Location;
use common\models\State;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile(\Yii::$app->homeUrl."css/custom/w3.css");
$firstname = isset($params['firstname'])?$params['firstname']:'';
$lastname = isset($params['lastname'])?$params['lastname']:'';
$email = isset($params['email'])?$params['email']:'';
$selected_rolename = isset($params['roleName'])?$params['roleName']:'';

$selected_division = isset($params['division'])?$params['division']:'';
$selected_role = isset($params['role'])?$params['role']:'';
$selected_location = isset($params['location'])?$params['location']:'';
$selected_state = isset($params['state'])?$params['state']:''; 

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
  
   <div class="row" style="height:20px">
  </div>	
  <div class="card card-collapse">
			<div class="card-head style-default">
				<div class="tools">
					<div class="btn-group">
						<a class="btn btn-icon-toggle btn-collapse" data-toggle="collapse"><i class="fa fa-angle-down"></i></a>
					</div>
				</div>
				<header>Search</header>
			</div><!--end .card-head -->
			<div class="card-body">
				<div class="program-search">
					<form method="post" >
						<div class="row">
							<div class="col-sm-3">
									<div class="form-group">
									<label class="control-label" for="searchreport-c_id">First Name</label>
									<input type="text" class="form-control" name="firstname" value="<?=$firstname?>">
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
									<div class="form-group">
									<label class="control-label" for="searchreport-c_id">Last Name</label>
									<input type="text" class="form-control" name="lastname" value="<?=$lastname?>">
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
									<div class="form-group">
									<label class="control-label" for="searchreport-c_id">Username / Email ID</label>
									<input type="text" class="form-control" name="email" value="<?=$email?>">
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
							<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Access Level</label>
									
									<?= Html::dropDownList('roleName', "$selected_rolename",MyCaar::getChildRoles('company_admin'),['prompt'=>'--Select--','class'=>'form-control']) ?> 
									
									 
									<div class="help-block"></div>
								</div>
							</div>
								
						</div>
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
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Search</button>  
							<a class="btn btn-danger" href="<?php echo Url::to(['company/index-user'])?>" >Clear Search </a>
						</div>
					</form>
				</div>
			</div><!--end .card-body -->
		</div><!--end .card -->
	
	
	<div class="small-padding"></div>
	  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
				'label'=>'Access Level',
			 	 'filter' => Html::activeDropDownList($searchModel, 'roleName',MyCaar::getChildRoles('company_admin'),['class'=>'form-control input-sm','prompt' => 'Role Name']),  			
			],
			[
				'attribute' => 'role',
				'value' =>'userRole.title',	
			],	
			/* [
				'attribute' => 'role',
				'label'=>'Position',
				'value' => function ($dataProvider){
						$role = Role::findOne($dataProvider->userProfile->role);
						return ($role)?$role->title:" (not set) ";
					},				
			], */
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

 <script>
		$('.card-head .tools .btn-collapse').on('click', function (e) {
			var card = $(e.currentTarget).closest('.card');
			materialadmin.AppCard.toggleCardCollapse(card);
		});
		
	</script>


