<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;

use common\models\Program;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>
	<div class="card">
		<div class ="card-body">
			<div class="program-search">
				<form method="post">
					<div class="form-group">
						<label class="control-label" for="searchreport-user_id">User ID</label>
						<?= Html::dropDownList('user', '',ArrayHelper::map(User::find()->where(['c_id'=>\Yii::$app->user->identity->c_id])->all(), 'id', 'username'),['prompt'=>'--Select--','class'=>'form-control']) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group">
						<label class="control-label" for="searchreport-unit_id">Unit ID</label>
						<?= Html::dropDownList('program', '',ArrayHelper::map(Program::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all(), 'program_id', 'title'),['prompt'=>'--Select--','class'=>'form-control']) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group">
						<label class="control-label" for="searchreport-c_id">First Name</label>
						<input type="text" class="form-control" name="firstname">
						<div class="help-block"></div>
					</div>
					<div class="form-group">
						<label class="control-label" for="searchreport-c_id">Last Name</label>
						<input type="text" class="form-control" name="lastname">
						<div class="help-block"></div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">Search</button>           
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php 
	$username ='';
	foreach($programs as $program)
	{
		$modules = $program->modules;
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
							<div class="mdl-card--border"><span class="mdl-text">'.$progress.'%</span><span class="mdl-label">'.$name.'</span></div>
						</div>
					</div></li>';
				}
			}
	
		echo '</ul>';
        echo'<div class="all_course al_pragram_width ">';
		foreach($modules as $p_key=>$module)
		{
			$units = $module->units;
			if(count($units) > 0)
			{
			//echo $p_key;
			if($p_key == 0)
				echo '<div class="course_listing al_single_course_width units-present-4">';
			else 
				echo '<div class="course_listing al_single_course_width units-present-4"  style="margin-left: -7px">'
			;
					echo '<div class="course_name">
                            <h2>
                                <strong>'.$module->title.'</strong>
                            </h2>
                    </div>
					<div class="course_units">
                        <ul>';

				foreach($units as $k=>$unit){
					if($k==0)
							echo "<li>";
					else 
						echo '<li class="margin" style="margin-left: -307px">';
						echo 
							'<div class="single_unit_title">
                                        '.$unit->title.'
                            </div>
							<div class="course_types">';
							foreach($users as $key => $user){
								if($user->user->isEnrolled($program->program_id))
								{
									echo ' <div class="course_indicate">
												<div class="assessement_item">
													<div name="unit1">';
													if(!$key)
														echo '<span class="first_heading">Aware</span>';
													else echo '<span class="first_heading" style="display: none">Aware</span>';
													$progress = $user->user->getUnitProgress($unit->unit_id);
													echo "<div name='unit1'>
															<a class='mdl-button mdl-js-button mdl-button--fab mdl-hover-{$progress['ap']} mdl-small-icon-{$progress['ap']}' href=''><span class='tooltiptext'><center>{$progress['ap']}</center></span>
															</a>
														</div>

													</div>

													<div name='unit1'>";
														//if(!$key)
															echo "<span class='first_heading'>Capable</span>";
														//else echo '<span class="first_heading" style="display: none">Capable</span>';
														$href= 'javascript:void(0);';
														if($progress['cp'] != 'grey')
															$href = Url::to(['test/cp-test','user_id'=>$user->user_id,'unit_id'=>$unit->unit_id]);
														echo "<div name='unit1'>

															<a class='mdl-button mdl-js-button mdl-button--fab mdl-hover-{$progress['cp']} mdl-small-icon-{$progress['cp']}' href=".$href."><span class='toolkit'><center>{$progress['cp']}</center></span>
															</a>

														</div>
													</div>


												</div>
											</div>";
								}//if enrolled
							}
								
					echo "</div></li>";
					//$i++;
				}		
			echo "</ul></div>";
		echo "</div>";
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
	
