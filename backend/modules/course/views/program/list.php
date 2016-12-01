<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programs';
$this->params['breadcrumbs'][] = $this->title;
$homeUrl = Yii::$app->homeUrl;
?>
<link type="text/css" rel="stylesheet" href="<?=Yii::$app->homeUrl?>css/theme-default/libs/nestable/nestable.css?1423393667" />
<h1><?= Html::encode($this->title) ?></h1>
<div class="card">

	<div class ="card-body">


    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Add Program', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<div class='row'>
		<div class='col-lg-12'>
			<div class='dd nestable-list'>
				<ol class='dd-list'>
					<?php foreach($dataProvider->models as $program){
						echo 					
						"<li class='dd-item tile' data-id='$program->program_id'>
							<div class='btn btn-default-dark'><i class='fa fa-list'></i> $program->title
							<span class='pull-right text-primary-light'>
								<a href='{$homeUrl}course/program/view?id={$program->program_id}' style='padding-right:3px'><span class='glyphicon glyphicon-eye-open'></span></a>
								<a href='{$homeUrl}course/program/update?id={$program->program_id}' style='padding-right:3px'><span class='glyphicon glyphicon-pencil'></span></a>
								<a href='{$homeUrl}course/program/delete?id={$program->program_id}' data-confirm='Are you sure you want to delete this item?' data-method='post' style='padding-right:3px'><span class='glyphicon glyphicon-trash'></span></a>
							</span>
							</div>
						";
						$modules = $program->modules;
						if(count($modules)>0){
							echo "<ol class='dd-list'>";
							foreach($modules as $module){
								echo "
									<li class='dd-item' data-id='$module->module_id'>
										<div class='btn btn-default'>$module->title
											<span class='pull-right text-primary'>
												<a href='{$homeUrl}course/module/update?id={$module->module_id}' style='padding-right:3px'><span class='glyphicon glyphicon-eye-open'></span></a>
												<a href='{$homeUrl}course/module/delete?id={$module->module_id}' data-confirm='Are you sure you want to delete this item?' data-method='post'  style='padding-right:3px'><span class='glyphicon glyphicon-trash'></span></a>
											</span>										
										</div>
									";
								$units = $module->units;
								if(count($units)>0){
									echo "<ol class='dd-list'>";
									foreach($units as $unit){
										echo "
									<li class='dd-item' data-id='$unit->unit_id'>
										<div class='btn btn-default-bright'>$unit->title
											<span class='pull-right text-primary-dark'>
												<a href='{$homeUrl}course/unit/update?id={$unit->unit_id}' style='padding-right:3px'><span class='glyphicon glyphicon-eye-open'></span></a>
												<a href='{$homeUrl}course/unit/delete?id={$unit->unit_id}' data-confirm='Are you sure you want to delete this item?' data-method='post' style='padding-right:3px'><span class='glyphicon glyphicon-trash'></span></a>
											</span>											
										</div>
									</li>";
									}
									echo "</ol>";
								}
								echo "</li>";
							}	
							echo "</ol>";
						}

						echo "</li>";
					}?>
				</ol>
			</div><!--end .dd.nestable-list -->
		</div><!--end .col -->

	</div><!--end .row -->
	</div>
</div>
<script src="<?=Yii::$app->homeUrl?>js/libs/nestable/jquery.nestable.js"></script>
<script>
$(document).ready(function(){
	$('.nestable-list').nestable();
});
</script>