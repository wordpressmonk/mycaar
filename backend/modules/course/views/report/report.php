<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;

use common\models\Program;
use common\models\User;

use common\models\Role;
use common\models\Division;
use common\models\Location;
use common\models\State;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile(\Yii::$app->homeUrl."css/custom/w3.css");
//if($params){
	$selected_user = isset($params['user'])?$params['user']:'';
	$selected_program = isset($params['program'])?$params['program']:'';
	$firstname = isset($params['firstname'])?$params['firstname']:'';
	$lastname = isset($params['lastname'])?$params['lastname']:'';
	$selected_role = isset($params['role'])?$params['role']:'';
	$selected_division = isset($params['division'])?$params['division']:'';
	$selected_location = isset($params['location'])?$params['location']:'';
	$selected_state = isset($params['state'])?$params['state']:'';
//}
?>

    <h1><?= Html::encode($this->title) ?></h1>
		<div class="card card-collapse card-collapsed">
			<div class="card-head style-primary">
				<div class="tools">
					<div class="btn-group">
						<a class="btn btn-icon-toggle btn-collapse" data-toggle="collapse"><i class="fa fa-angle-down"></i></a>
					</div>
				</div>
				<header>Search</header>
			</div><!--end .card-head -->
			<div class="card-body" style="display:none">
				<div class="program-search">
					<form method="post">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">User ID</label>
									<?= Html::dropDownList('user', "$selected_user",ArrayHelper::map(User::find()->where(['c_id'=>\Yii::$app->user->identity->c_id])->all(), 'id', 'username'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="searchreport-unit_id">Unit ID</label>
									<?= Html::dropDownList('program', "$selected_program",ArrayHelper::map(Program::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all(), 'program_id', 'title'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="searchreport-c_id">First Name</label>
									<input type="text" class="form-control" name="firstname" value="<?=$firstname?>">
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="searchreport-c_id">Last Name</label>
									<input type="text" class="form-control" name="lastname" value="<?=$lastname?>">
									<div class="help-block"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Role</label>
									<?= Html::dropDownList('role', "$selected_role",ArrayHelper::map(Role::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all(), 'role_id', 'title'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Division</label>
									<?= Html::dropDownList('division', "$selected_division",ArrayHelper::map(Division::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all(), 'division_id', 'title'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Location</label>
									<?= Html::dropDownList('location', "$selected_location",ArrayHelper::map(Location::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all(), 'location_id', 'name'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">Role</label>
									<?= Html::dropDownList('state', "$selected_state",ArrayHelper::map(State::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all(), 'state_id', 'name'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Search</button>  
							<a class="btn btn-danger" href="<?= Url::to(['report/search'])?>" >Reset </a>
						</div>
					</form>
				</div>
			</div><!--end .card-body -->
		</div><!--end .card -->

	<?php 
	$username ='';
	foreach($programs as $program)
	{
		$modules = $program->publishedModules;
		if(count($modules) > 0 && count($program->programEnrollments) > 0)
		{
		echo '<div class="mdl-grid">
			<div class="mdl-cell mdl-cell-8-col">
				<span class="mdl-program"><h4><span class="mdl-test">Program</span> : '.$program->title.'</h4></span>
			</div>
		</div>';
		echo '<div class="horizontal al_cpp_category_16">';
		echo '<ul class="name_list" >';
		
			foreach($users as $user){
				if($user->user->isEnrolled($program->program_id)){
					$name = $user->firstname. " ". $user->lastname;
					if($name == '')
						$name = $user->user->username;
					//$progress = 0;
					$progress = $user->user->getProgramProgress($program->program_id);
					echo '
					<li><div class="mdl-grid" >
						<div class="mdl-cell mdl-cell--3-col mdl-bar" >
							<div class="mdl-card--border">
								<div class="w3-progress-container">
									<div class="w3-progressbar" style="width:'.$progress.'%">'.$progress.'%</div><span class="mdl-label">'.$name.'</span></div>
								</div>
							</div>
						</div>
					</li>';
				}
			}
	
		echo '</ul>';
		//program bar starts from here
        echo'<div class="all_course al_pragram_width ">';
		foreach($modules as $p_key=>$module)
		{
			$no_user_enrolled = true;
			$str = '';
			$units = $module->publishedUnits;
			if(count($units) > 0)
			{
			//$str.= $p_key;
			if($p_key == 0)
				$str.= '<div class="course_listing al_single_course_width units-present-4">';
			else 
				$str.= '<div class="course_listing al_single_course_width units-present-4">'
			;
					$str.= '<div class="course_name">
                            <h2>
                                <strong>'.$module->title.'</strong>
                            </h2>
                    </div>
					<div class="course_units">
                        <ul>';

				foreach($units as $k=>$unit){
					if($k==0)
						$str.= "<li>";
					else 
						$str.= '<li class="margin" style="margin-left: -304px">';
						$str.= 
							'<div class="single_unit_title">
                                        '.$unit->title.'
                            </div>
							<div class="course_types">';
							foreach($users as $key => $user){
								if($user->user->isEnrolled($program->program_id))
								{
									$no_user_enrolled = false;
									$str.= ' <div class="course_indicate">
												<div class="assessement_item">
													<div name="unit1">';
													if(!$key)
														$str.= '<span class="first_heading">Aware</span>';
													else $str.= '<span class="first_heading" style="display: none">Aware</span>';
													$progress = $user->user->getUnitProgress($unit->unit_id);
													//print_r($progress);
													$str.= "<div name='unit1'>
															<a class='mdl-button mdl-js-button mdl-button--fab mdl-hover-{$progress['ap']} mdl-small-icon-{$progress['ap']}' href='javascript:void(0);'><span class='toolkit'><center>{$progress['ap']}</center></span>
															</a>
														</div>

													</div>

													<div name='unit1'>";
														//if(!$key)
															$str.= "<span class='first_heading'>Capable</span>";
														//else $str.= '<span class="first_heading" style="display: none">Capable</span>';
														$href= 'javascript:void(0);';
														$onClick = '';
														if($progress['cp'] != 'grey')
															$href = Url::to(['test/cp-test','user_id'=>$user->user_id,'unit_id'=>$unit->unit_id]);
														if($user->user_id == \Yii::$app->user->id){
															$onClick = "popUpNotAllowed();";
															$href= 'javascript:void(0);';
														}
														if($progress['cp'] == 'green'){
															$onClick = "popUpCompleted();";
															$href= 'javascript:void(0);';
														}	
														$str.= "<div name='unit1' id='{$progress['cp']}'>

															<a class='mdl-button mdl-js-button mdl-button--fab mdl-hover-{$progress['cp']} mdl-small-icon-{$progress['cp']}' href=".$href." onClick=".$onClick."><span class='toolkit'><center>{$progress['cp']}</center></span>
															</a>

														</div>
													</div>


												</div>
											</div>";
								}//if enrolled
							}
								
					$str.= "</div></li>";
					//$i++;
				}		
			$str.= "</ul></div>";
		$str.= "</div>";
		if(!$no_user_enrolled)
			echo $str;
			} //if unit count
		}
		echo "</div></div>";
		?>


		<?php
		} //module count && enrollment count
	}
	
	//FOR DEBUG
	foreach($users as $user){
	$progress = $user->user->getProgramProgress(1);
	} 
	?>
	<script>
		$('.card-head .tools .btn-collapse').on('click', function (e) {
			var card = $(e.currentTarget).closest('.card');
			materialadmin.AppCard.toggleCardCollapse(card);
		});
		function popUpNotAllowed(){
			alert("Sorry, you're not able to complete your own capability test!");
		}
		function popUpCompleted(){
			alert("Sorry you can't able to attend this capability test, it is already completed!");
		}
	</script>
