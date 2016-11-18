<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\MyCaar;
use common\models\Program;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Enroll User';
$this->params['breadcrumbs'][] = $this->title;
?>

  <script src="<?=Yii::$app->homeUrl;?>enroll/js/jquery.selectlistactions.js"></script>  
  <!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
  <link rel="stylesheet" href="<?=Yii::$app->homeUrl;?>enroll/css/site.css">
    
<h1><?= Html::encode($this->title) ?></h1>
<div class="card">

    <div class="card-body">

	<?php $form = ActiveForm::begin(['action' => ''.Yii::$app->homeUrl.'user/company/enroll-user','id'=>'enrol_form']); ?>
	
	<?php 
	
	$program =Program::find()->select(['program_id','title'])->where(['company_id' =>Yii::$app->user->identity->c_id])->all();

	?>
	
	<div class="form-group field-userprofile-division has-success">
		<label class="control-label" for="userprofile-division">Select Program</label>
		<select id="program_id" class="form-control" name="program_id" required >
		<option value="">--Program--</option>
		<?php if($program)
		{
			foreach($program as $tmp)
			{
				echo "<option value='".$tmp->program_id."'>".$tmp->title."</option>";
			}
		}	?>

		</select>
		<div class="help-block"></div>
   </div>

   
   
   <div class="container" style="width:100%; margin-top: 50px;  height: 140px;display:none" id="userlist" >
			<div class="row style-select">
			<div class="col-md-12">
				<div class="subject-info-box-1">
					<label>Please Click on Student to Select</label>
					<select multiple class="form-control" id="unselecteduser" name="unselecteduser[]">
					</select>
				</div>

				<div class="subject-info-arrows text-center">
					<br /><br />
					<input type='button' id='btnAllRight' value='>>' class="btn btn-default" /><br />
					<input type='button' id='btnRight' value='>' class="btn btn-default" /><br />
					<input type='button' id='btnLeft' value='<' class="btn btn-default" /><br />
					<input type='button' id='btnAllLeft' value='<<' class="btn btn-default" />
				</div>

				<div class="subject-info-box-2">
					<label>Selected Students in this Program</label>
					<select multiple class="form-control" id="selecteduser" name="selecteduser[]">
					</select>
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
   </div>
   
   
   
   
   
			<div class="form-group">
			<?= Html::submitButton( 'Save Changes', ['class' =>'btn btn-success' ]) ?>
		</div>
		
		<?php ActiveForm::end(); ?>
	
	</div>
</div>



<script>
        $('#btnRight').click(function (e) {
            $('select').moveToListAndDelete('#unselecteduser', '#selecteduser');
            e.preventDefault();
        });

        $('#btnAllRight').click(function (e) {
            $('select').moveAllToListAndDelete('#unselecteduser', '#selecteduser');
            e.preventDefault();
        });

        $('#btnLeft').click(function (e) {
            $('select').moveToListAndDelete('#selecteduser', '#unselecteduser');
            e.preventDefault();
        });

        $('#btnAllLeft').click(function (e) {
            $('select').moveAllToListAndDelete('#selecteduser', '#unselecteduser');
            e.preventDefault();
        });
		
		$(document).ready(function(){
			$( "#program_id" ).change(function() {
				
				var programid = $(this).val();
				if($.trim(programid) !="")
				{
					$.ajax({ 
				  url: "<?=Yii::$app->homeUrl;?>user/company/unselected-list",
				  data:{
					  program_id:$(this).val(),					  
					},
				  type: "POST",
				  success: function(data){		  					
						 $("#unselecteduser").html(data);
						}
					}); 

				$.ajax({ 
				  url: "<?=Yii::$app->homeUrl;?>user/company/selected-list",
				  data:{
					  program_id:$(this).val(),					  
					},
				  type: "POST",
				  success: function(data){		  						
						 $("#selecteduser").html(data);
						}
					}); 
					$("#userlist").show();					
				} else {
					$("#userlist").hide();
				}
			});
		}); 
		
		
		
		$( "#enrol_form" ).submit(function( event ) {
			$("#unselecteduser option").each(function()
			{
			 $(this).prop('selected', true);	
			});
			
			$("#selecteduser option").each(function()
			{
			 $(this).prop('selected', true);	
			});			
		});
		
    </script>
	
	
