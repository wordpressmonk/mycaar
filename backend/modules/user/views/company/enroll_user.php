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
		<?php if($program_id){ ?>				
				<?=GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'layout' => '{items}',
				'columns' => [
					[	
					'class' => 'yii\grid\CheckboxColumn',
					'checkboxOptions' => function ($model, $key, $index, $column) use ($program_id) {
						return ['checked' =>false];
					}
					],
					'username', 
				 	/* [
						'format' => 'html',
						'label' => 'Status',
					 	'filter'=> Html::dropDownList('enrollcheck','',[''=>'--Status--',0=>'Enroll',1=>'UnEnrol'],['class'=>'form-control input-sm']), 						
						'value' => function ($model, $key, $index, $column) use ($program_id) {
							//$stat = $model->isEnrolled($program_id);
						//return $model->isEnrolled($program_id)?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-close text-danger"></i>';
					
							//return $model->isEnrolled($program_id)?'0':'1';
					} 
						
					],	 */ 
					'enrolled',
					'id',
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