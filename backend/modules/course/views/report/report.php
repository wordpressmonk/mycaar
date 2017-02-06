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
	$selected_page = isset($params['page'])?$params['page']:0;
//}
?>

    <!--<div class="mdl-grid mdl-home">
					<div class="mdl-cell mdl-cell-8-col" style="margin: 0px 32px 0px 4px !important;">
						<h1 class="mdl-sidebar"><strong>Dashboard & Search</strong></h1>
					</div>

	</div>-->

	
		<div class="card card-collapse card-collapsed small-padding">
			<div class="card-head card-head-xs style-default">
				<div class="tools">
					<div class="btn-group">
						<a class="btn btn-icon-toggle btn-collapse" data-toggle="collapse"><i class="fa fa-angle-down"></i></a>
					</div>
				</div>
				<header>Search</header>
			</div><!--end .card-head -->
			<div class="card-body" style="display:none">
				<div class="program-search">
					<form method="post" id="filter_form">
						<div class="row">
							<!--<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="searchreport-user_id">User ID</label>
									<?php //echo Html::dropDownList('user', "$selected_user",ArrayHelper::map(User::find()->where(['c_id'=>\Yii::$app->user->identity->c_id,'status'=>10])->all(), 'id', 'username'),['prompt'=>'--Select--','class'=>'form-control']) ?>
									<div class="help-block"></div>
								</div>
							</div>-->
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="searchreport-unit_id">Program</label>
									<?= Html::dropDownList('program', "$selected_program",ArrayHelper::map(Program::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->orderBy('title')->all(), 'program_id', 'title'),['prompt'=>'--Select--','class'=>'form-control','id'=>'program']) ?>
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
				
						<input type="hidden" class="form-control" name="page" id="page" value="<?= $selected_page ?>">
						<div class="form-group">
							<button type="submit" id="submit_check"  class="btn btn-primary">Search</button>  
							<a class="btn btn-danger" href="<?php echo Url::to(['report/search'])?>" >Clear Search </a>
						</div>
					</form>
				</div>
			</div><!--end .card-body -->
		</div><!--end .card -->
		<!--<div class="mdl-grid">
				<div class="mdl-cell mdl-cell-8-col">
					<span class="mdl-welcome"><h3>Welcome <?=\Yii::$app->user->identity->fullname?></h3></span>
					<span class="mdl-current"><h3>Current Programs :</h3></span>
				</div>
		</div>-->
	<?php 
	$check_output ='';	
	//echo count($users);
	foreach($programs as $program)
	{
		
		$no_user_enrolled = true;
		foreach($users as $key => $user){
			if($user->user->isEnrolled($program->program_id))
				{
					$no_user_enrolled = false;
				}
		}
			
		$modules = $program->publishedModules;
		if(!$no_user_enrolled && count($modules) > 0 && count($program->programEnrollments) > 0)
		{
		$check_output .= $program->program_id;
		echo '<div class="mdl-grid row">
			<div class="program_test" style="min-width:100%">
				<span class="mdl-program"><h4><span class="mdl-test">Program </span> : '.$program->title.'</h4>
			</span>';
		//if(count($users) > 0 && count($program->programEnrollments)>0)
		echo Html::beginForm(['/course/export/export'], 'post')
										.Html::input('hidden', 'p_id', $program->program_id, ['class' =>'form-control'])
										.Html::input('hidden', 'params', serialize($params), ['class' =>'form-control'])
										. Html::submitButton(
											'Download Report',
											['class' => 'btn ink-reaction btn-raised btn-xs btn-info']
										)
										. Html::endForm();
										
		echo '<div style="float: right;">
						<ul>
							<li>
								<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelgreen mdl-icon" data-upgraded=",MaterialButton">Green</button><span class="mdl-complete">Complete</span>
								<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelyellow mdl-yellow" data-upgraded=",MaterialButton"> Amber</button><span class="mdl-complete">In Progress</span>
								<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelred mdl-darkred" data-upgraded=",MaterialButton">Red</button><span class="mdl-complete">- Not Commenced</span>
								<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelgrey mdl-lightgrey" data-upgraded=",MaterialButton">Grey</button><span class="mdl-complete">- Not applicable</span>
							</li>
						</ul>
					</div>';
		echo '</div>
		</div>';

		echo '<div class="horizontal al_cpp_category_16">';
		echo '<ul class="name_list" >';
	
			foreach($users as $user){
				if($user->user->isEnrolled($program->program_id)){
					$name = $user->userProfile->firstname. " ". $user->userProfile->lastname;
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
                                '.$module->title.'
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
							'<div class="single_unit_title" style="overflow:visible; white-space:initial;">
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
													
													$onClick = "";
													$target = "";
													$href="javascript:void(0);";
													if($user->user_id == \Yii::$app->user->id)
													{
														// Client Asked to Go to Learn PAge By Arivu
														//$href = \Yii::$app->urlManagerFrontEnd->baseUrl."/test/aw-test?u_id=".$unit->unit_id;
														$href = \Yii::$app->urlManagerFrontEnd->baseUrl."/test/learn?u_id=".$unit->unit_id;
														
														$target = "target='_blank'";
													}
													else
													{
														$onClick = "popUpNotAllowedAware();";
													}
													//print_r($progress);
													$str.= "<div name='unit1'>
															<a class='mdl-button mdl-js-button mdl-button--fab mdl-hover-{$progress['ap']} mdl-small-icon-{$progress['ap']}' href='$href' $target onClick=".$onClick." ><span class='toolkit'><center>{$progress['ap']}</center></span>
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
															$href = Url::to(['test/cp-test','user_id'=>$user->user_id,'unit_id'=>$unit->unit_id,'data'=>serialize($params)]);
														if($user->user_id == \Yii::$app->user->id && $progress['cp'] != 'grey'){
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
		if(!$no_user_enrolled) echo $str;
			} //if unit count
		}
		echo "</div></div>";
		
		?>
		<?php
			  if(!empty($selected_program)||!empty($firstname)||!empty($lastname)||!empty($selected_role)||!empty($selected_division)||!empty($selected_location)||!empty($selected_state))
				$usercount = (isset($usersfiltercount))?count($usersfiltercount):0;
			else  
				$usercount = count($program->programEnrollments); 
			//$usercount = (isset($usersfiltercount))?count($usersfiltercount):0;
			
			$result1  = $usercount/50;
			$test = floor($result1);
			if($usercount%50 == 0)
				$result = $test;
			 else 
				 $result = $test + 1;
			 
			if($usercount > 50)
			{
			 for($i=0; $i<$result; $i++ )
			 {
				 $j = $i;
			?>
		
		
		<span  for="<?= $program->program_id ?>" class="select_page <?php if($selected_page == $i){ echo 'selectedpage';} else { echo 'unselectedpage'; } ?> " data-id="<?= $i ?>" > 
			<a > <?= $j+1; ?></a>
		</span>
		
		<?php
			 }
			}	
		
		
		} //module count && enrollment count
		//else echo "No results found!";
	}
	if($check_output == '')
		echo "No results found!";
	//FOR DEBUG
	foreach($users as $user){
	$progress = $user->user->getProgramProgress(1);
	} 
	?>
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header style-primary">
          <h4 class="modal-title text-bold text-xxl">Sorry!</h4>
        </div>
        <div class="modal-body text-medium">
          <p>The <strong>show</strong> method shows the modal and the <strong>hide</strong> method hides the modal.</p>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="modal fade" id="myModal4" role="dialog">
				<div class="modal-dialog">
				
				  <!-- Modal content-->
				  <div class="modal-content">
				
					<div class="modal-body text-medium">
					  <!--<img src="<?=\Yii::$app->homeUrl;?>/img/warning1.png" />-->
					  
					   <div class="check_Popup_Capability">					
						<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
						<i class="fa fa-times " data-dismiss="modal" aria-hidden="true"></i>
						<p class="capability_3">Sorry</p>
						<p class="capability_1">You are not able to complete your own capabilty test.</p>
						<p class="capability_4">Please refer to your assigned coach or assessor to complete this step.</p>
						<button class="capability_2 " data-dismiss="modal" aria-hidden="true" >Go Back</button>
    				 </div> 
					 
					</div>
				  </div>
				  
				</div>
			</div>
			
			<div class="modal fade" id="myModal3" role="dialog">
				<div class="modal-dialog">
				
				  <!-- Modal content-->
				  <div class="modal-content">
				
					<div class="modal-body text-medium">
					 <!--<img src="<?=\Yii::$app->homeUrl;?>/img/warning2.png" />-->
					 	
					  <div class="check_Popup_Capability">					
						<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
						<i class="fa fa-times " data-dismiss="modal" aria-hidden="true"></i>
						<p class="capability_3">Sorry</p>
						<p class="capability_1">You are not able to open another persons awareness test.</p>					
						<button class="capability_2 " data-dismiss="modal" aria-hidden="true" >Go Back</button>
    				 </div> 
					 
					</div>
				  </div>
				  
				</div>
			</div>
			
			<div class="modal fade" id="myModal5" role="dialog">
				<div class="modal-dialog">
				
				  <!-- Modal content-->
				  <div class="modal-content">
				
					<div class="modal-body text-medium">
					 <!--<img src="<?=\Yii::$app->homeUrl;?>/img/warning2.png" />-->
					 	
					  <div class="check_Popup_Capability">					
						<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
						<i class="fa fa-times " data-dismiss="modal" aria-hidden="true"></i>
						<p class="capability_3">Oops</p>
						<p class="capability_1">This Capability test has already been competed</p>					
						<button class="capability_2 " data-dismiss="modal" aria-hidden="true" >Go Back</button>
    				 </div> 
					 
					</div>
				  </div>
				  
				</div>
			</div>
			
	<script>
		$('.card-head .tools .btn-collapse').on('click', function (e) {
			var card = $(e.currentTarget).closest('.card');
			materialadmin.AppCard.toggleCardCollapse(card);
		});
		function popUpNotAllowed(){
			//$(".modal-body").html("Sorry, you're not able to complete your own capability test!");
			$("#myModal4").modal("show");
			//alert("Sorry, you're not able to complete your own capability test!");
		}
		function popUpCompleted(){
			//$(".modal-body").html("Sorry you can't able to attend this capability test, it is already completed!");
			//$("#myModal").modal("show");
			//alert("Sorry you can't able to attend this capability test, it is already completed!");
			$("#myModal5").modal("show");
		}
		function popUpNotAllowedAware(){			
			$("#myModal3").modal("show");			
		}
	</script>
	<script>
	$(document).ready(function(){
		
		$(".select_page").click(function(){
			var p_id = $(this).attr("for");
			$('select[name^="program"] option[value="'+p_id+'"]').attr("selected","selected");			
			$("#page").val($(this).attr("data-id"));			
			$( "#filter_form" ).submit();		
		});
		
		$("#submit_check").click(function(){ 
			$("#page").val(0);
		});
			
	});
	</script>
	
	<style>
	.selectedpage{
		padding:5px 7px;
		background-color:grey;
		margin-left:8px;
		cursor: pointer;
	}
	.unselectedpage{
		padding:5px 7px;
		background-color:#ccc;
		margin-left:8px;
		cursor: pointer;
	}
	.selectedpage a{ 
		color:#fff;
		text-decoration:none;
		font-weight:bold;
	}
	.unselectedpage a{ 
		color:#000;
		text-decoration:none;
		font-weight:bold;
	}
	
	
		/*For Chrome */
@media screen and (-webkit-min-device-pixel-ratio:0) {
	.name_list
    {
        float: left;
        bottom: -140px;
        position: relative;
    
    }
    }
	
	</style>
	
