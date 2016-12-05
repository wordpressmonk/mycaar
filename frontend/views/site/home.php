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
	<?php if(Yii::$app->session->getFlash('error')!='') {?>
	<div class="alert alert-danger" role="alert">
		<strong>Oh snap!</strong> <?= Yii::$app->session->getFlash('error'); ?>.
	</div>
	
	<?php 
	}
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
							<div class="mdl-card--border"><span class="mdl-text">'.$progress.'%</span><span class="mdl-label">'.$name.'</span></div>
						</div>
					</div></li>';
				}
			}
	
		echo '</ul>';
        echo'<div class="all_course al_pragram_width ">';
		foreach($modules as $p_key=>$module)
		{
			$units = $module->publishedUnits;
			if(count($units) > 0)
			{
			//echo $p_key;
			if($p_key == 0)
				echo '<div class="course_listing al_single_course_width units-present-4">';
			else 
				echo '<div class="course_listing al_single_course_width units-present-4" >'
			;
					echo '<div class="course_name" style="position:relative">
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
						echo '<li class="margin" style="margin-left: -304px">';
						echo 
							'<div class="single_unit_title"><a href="'.Url::to(['test/learn','u_id'=>$unit->unit_id]).'">
                                        '.$unit->title.'
                            </a></div>
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
													$url = Url::to(['test/aw-test','u_id'=>$unit->unit_id]);
													echo "<div name='unit1'>
															<a class='mdl-button mdl-js-button mdl-button--fab mdl-hover-{$progress['ap']} mdl-small-icon-{$progress['ap']}' href='$url'><span class='toolkit'><center>{$progress['ap']}</center></span>
															</a>
														</div>

													</div>

													<div name='unit1'>";
														//if(!$key)
															echo "<span class='first_heading'>Capable</span>";
														//else echo '<span class="first_heading" style="display: none">Capable</span>';
														$href= 'javascript:void(0);';
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
	
