<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

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
	foreach($programs as $program){
		echo '<div class="mdl-grid">
			<div class="mdl-cell mdl-cell-8-col">
				<span class="mdl-program"><h4 style="font-size:18px"><span class="mdl-test">Program</span> : checktest1</h4></span>
			</div>
		</div>';
		echo '<div class="horizontal al_cpp_category_16">
                <div class="all_course al_pragram_width ">';
		foreach($program->modules as $p_key=>$module){
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

				foreach($module->units as $k=>$unit){
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
								echo ' <div class="course_indicate">
												<div class="assessement_item">
													<div name="unit1">';
													if(!$key)
														echo '<span class="first_heading">Aware</span>';
													else echo '<span class="first_heading" style="display: none">Aware</span>';
													
													echo '<div name="unit1">
															<a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
															</a>
														</div>

													</div>

													<div name="unit1">';
														if(!$key)
															echo '<span class="first_heading">Capable</span>';
														else echo '<span class="first_heading" style="display: none">Capable</span>';
														echo '<div name="unit1">

															<a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red" href=""><span class="toolkit"><center>Red</center></span>
															</a>

														</div>
													</div>


												</div>
											</div>';
							}
								
					echo "</div></li>";
					//$i++;
				}		
			echo "</ul></div>";
		echo "</div>";
		}
		echo "</div></div>";
	}
	foreach($users as $user){
			echo '
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--3-col mdl-bar">
                    <div class="mdl-card--border" style="border: 1px solid #008000;margin:6px;height:27px"><span class="mdl-text">0%</span><span class="mdl-label">'.$user->firstname.'</span></div>
					</div>
				</div>';
	}
	?>