<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\MyCaar;
use common\models\Program;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Enroll User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="small-padding">
<div class="card">
<div class="card-head style-primary"><header>Enroll </header></div>
<div class="card-body">

	<?=Html::beginForm('enroll-user','post');?>

	<div class="row">
		<div class="col-md-5" >
			<?=Html::dropDownList(
				'Program',
				$program_id,
				ArrayHelper::map(Program::find()->where(['company_id' =>Yii::$app->user->identity->c_id])->all(),'program_id', 'title'),
				['prompt'=> 'Select',
				 'id' => 'program_select', 'class' => 'form-control','required'=>'required']
			);?>
		</div>
		<div class="col-md-5" >
		<?=Html::dropDownList('action','',[''=>'Mark selected as: ','enrolled'=>'Enroll','unenrolled'=>'UnEnrol'],['class'=>'form-control','required'=>'required'])?>
		</div>
		<div class="col-md-2" >
			<?=Html::submitButton('Change', ['class' => 'btn btn-success pull-right',]);?>
		</div>
</div>
<div class="small-padding"></div>
		<?php if($program_id){ 
		
		$array = [
					['id' => '1', 'name' => 'Enroll'],
					['id' => '0', 'name' => 'UnEnrol'],
				 ];
?>				
				<?=GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'layout' => '{items}',
				'columns' => [
					[	
					'class' => 'yii\grid\CheckboxColumn',
					 'checkboxOptions' => function ($data){
						return ['checked' =>false,'value'=>$data['id']];
					}, 
				
					],
				
					'username', 				
					[
						'format' => 'html',
						'label' => 'Status',					 
						'filter' => Html::activeDropDownList($searchModel, 'enrollcheck', ArrayHelper::map($array, 'id', 'name'),['class'=>'form-control input-sm','prompt' => '--Status--']),
						 'value' => function ($data){
						return ($data['is_enrolled']==1)?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-close text-danger"></i>';
						} 					
					],					
				],
				]);  ?>
		<?php } ?>
		<?= Html::endForm();?> 

	</div>	
</div>	
</div>
<script>


			$( "#program_select" ).change(function() {		
console.log("changing");			
				var programid = $(this).val();				
					window.location.href = "<?=Yii::$app->homeUrl;?>user/company/enroll-user?program_id="+programid;
			});
		
</script>					