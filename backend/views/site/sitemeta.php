<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SiteMeta */
/* @var $form ActiveForm */
?>
<div class="user-create">

    <h1>Update Site Logos</h1>
<div class="card">
	<div class="card-body">

    <?php $form = ActiveForm::begin(['action' =>['site/sitemeta'], 'id' => 'forum_post', 'method' => 'POST','options' => ['enctype'=>'multipart/form-data']]); ?>

	
	  <?php echo $form->field($model, 'meta_key')->textInput(['class'=>'form-control','value'=>'right-side-logo']); ?>
	  <?php //echo $form->field($model, 'meta_key[]')->textInput(['class'=>'form-control','value'=>'left-side-logo']); ?>
	  
	  <?php echo $form->field($model, 'meta_value')->fileInput(['class'=>'form-control']); ?>
	  <?php //echo $form->field($model, 'meta_value[]')->fileInput(['class'=>'form-control']); ?>
	  
	 <!--<div class="form-group field-sitemeta-meta_value">
		<label class="control-label" for="sitemeta-meta_value">Meta Value</label>
		<input id="sitemeta-meta_value" class="form-control" name="SiteMeta[meta_value][]" value="image2" type="text">
		<div class="help-block"></div>
	</div>

	<div class="form-group field-sitemeta-meta_value">
		<label class="control-label" for="sitemeta-meta_value">Meta Value</label>
		<input id="sitemeta-meta_value" class="form-control" name="SiteMeta[meta_value][]" value="image2" type="text">
		<div class="help-block"></div>
	</div>-->

		<!--<div class="form-group field-sitemeta-meta_value has-success">
			<label class="control-label" for="sitemeta-meta_value">Right Side Logo</label>
			<img id='rightlogo' style="cursor:pointer" src="<?=Yii::$app->homeUrl."img/CAAR-Logo2.png" ?>" height="100px"/>			
				<input id="sitemeta-meta_value" style="display:none" class="form-control right-side-logo"  name="SiteMeta[meta_value][]" type="file">
			<div class="help-block"></div>
		</div>

		<div class="form-group field-sitemeta-meta_value  has-success">
			<label class="control-label" for="sitemeta-meta_value">Left Side Logo</label>
			<img id='leftlogo' style="cursor:pointer" src="<?=Yii::$app->homeUrl."img/CAAR-Logo2.png" ?>"  height="100px"/>
				<input id="sitemeta-meta_value"  style="display:none" class="form-control left-side-logo" name="SiteMeta[meta_value][]" type="file">
			<div class="help-block"></div>
		</div>-->

    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

	</div>
</div><!-- site-sitemeta -->
</div>

  <script type="text/javascript"> 
  $(function(){	
			$('.right-side-logo').change( function(event) {
				var tmppath = URL.createObjectURL(event.target.files[0]);
				$("#rightlogo").fadeIn("fast").attr('src',tmppath);
			});
			
			$('.left-side-logo').change( function(event) {
				var tmppath = URL.createObjectURL(event.target.files[0]);
				$("#leftlogo").fadeIn("fast").attr('src',tmppath);
			});
  });  
  </script>
  <script>
 $(document).ready(function () {
	 
	  $("#rightlogo").click(function() {	
			$( ".right-side-logo" ).click();
		  });
		  
	 $("#leftlogo").click(function() {	
			$( ".left-side-logo" ).click();
		  });	  
 });
</script>
