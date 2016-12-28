<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\UnitElement;

$this->registerCssFile(\Yii::$app->homeUrl."css/custom/form-builder.css");
$this->registerCssFile(\Yii::$app->homeUrl."css/custom/form-render.css");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/form-builder.js");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/form-render.js");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/waitingfor.js");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/jquery-ui.min.js");

?>
<style>
button#frmb-0-view-data,button#frmb-4-view-data,button#frmb-2-view-data{
	display:none;
}
</style>
<div class="section-body contain-lg">
<h2 class="col-md-9">Update Lesson: <?=$model->title?></h2>


<div class="row">

		
	
	<div class="col-lg-12">
	<h4 class="small-padding">[ Program: <a href="<?= Url::to(['program/view','id'=>$module->program->program_id])?>"><?=$module->program->title?></a> , Module: <a href="<?= Url::to(['module/update','id'=>$module->module_id])?>"><?=$module->title?> ]</a></h4>
	<div class="card tabs-left style-default-light">
		<ul id="sortable" class="card-head nav nav-tabs tabs-info" data-toggle="tabs">
			<?php foreach($module->units as $unit){
				if($unit->unit_id == $model->unit_id)
					echo '<li id="unit_'.$unit->unit_id.'" class="ui-state-default active"><a href="#tab1">'.substr($unit->title,0,12).'..</a></li>';
				else
					echo '<li id="unit_'.$unit->unit_id.'" class="ui-state-default"><a class="unit_view" data-unit_id="'.$unit->unit_id.'" href="#tab2">'.substr($unit->title,0,12).'..</a></li>';
			}?>
			<li class="ui-state-disabled"><a class="unit_view" data-unit_id="new" href="#tab2">Add New Lesson</a></li>
		</ul>
		<div class="card-body tab-content style-default-bright">
		<div class="tab-pane active" id="tab1">
			<div class="panel-group" id="unit_accordian">
				<div class="card panel expanded">
					<div class="card-head style-info" data-toggle="collapse" data-parent="#unit_accordian" data-target="#unit_details" aria-expanded="true">
						<header>Lesson Details</header>
						<div class="tools">
							<a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
						</div>
					</div>
					<div id="unit_details" class="collapse" aria-expanded="true">
						<div class="card-body">
							
							<div class="checkbox checkbox-styled checkbox-info  pull-right">
								<label>
								<?php 
									if($model->status )
										$checked = "checked";
									else $checked = "";
								?>
									<input id="unit_status" type="checkbox" <?=$checked?>>
									<span>Publish</span>
								</label>
							</div>
							
							<div class="form-group field-unit-title required">
								<label>Lesson Title</label>
								<input type="text" id="unit_title" class="form-control" value="<?=$model->title ?>">
								<div class="help-block"></div>
								
							</div>

							<div class="form-group">
								<div class="checkbox checkbox-styled">
									<label>
										<input type="checkbox" value="">
										<span>Show Lesson Details Page</span>
									</label>
								</div>
							</div>
							<?php 
								$element = UnitElement::find()->where(['unit_id'=>$model->unit_id,'element_type'=>'page'])->one();
								$data = json_decode($element->content);
								$formdata = $data->html;
								$formdata = str_replace("'", "\'", $formdata);
								$formdata = str_replace(array("\r", "\n"), '', $formdata);
								//echo $formdata;die;
							?>
							<div id="build-wrap"></div>
						</div>
					</div>
				</div><!--end .panel -->
				<br>
				<div class="card panel">
					<div class="card-head style-info collapsed" data-toggle="collapse" data-parent="#unit_accordian" data-target="#awareness_test" aria-expanded="false">
						<header>Awareness Test</header>
						<div class="tools">
							<a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
						</div>
					</div>
					<div id="awareness_test" class="collapse" aria-expanded="false">
						<div class="card-body">
							<?php 
								$element = UnitElement::find()->where(['unit_id'=>$model->unit_id,'element_type'=>'aw_data'])->one();
								$aw_data = $element->content;
								//print_r($aw_data);die;
								//$aw_data = str_replace("&amp;", "&", $aw_data);
								$aw_data = html_entity_decode($aw_data);
								$aw_data = str_replace("&amp;", "&", $aw_data);								
								$aw_data = str_replace("'", "\'", $aw_data);
								//$aw_data = str_replace('"', '&quot;', $aw_data);
							?>
						<div id="aware_form"></div>
						</div>
					</div>
				</div><!--end .panel -->
				<br>
				<div class="card panel">
					<div class="card-head style-info collapsed" data-toggle="collapse" data-parent="#unit_accordian" data-target="#capability-test" aria-expanded="false">
						<header>Capability Test</header>
						<div class="tools">
							<a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
						</div>
					</div>
					<div id="capability-test" class="collapse" aria-expanded="false">
						<div class="card-body">
							<?php 
								$element = UnitElement::find()->where(['unit_id'=>$model->unit_id,'element_type'=>'cap_data'])->one();
								$cp_data = $element->content;
								$cp_data = html_entity_decode($cp_data);
								$cp_data = str_replace("'", "\'", $cp_data);
								//$cp_data = str_replace(array("\r", "\n"), '', $cp_data);
							?>
						<div id="capability_form"></div>
						</div>
					</div>
				</div><!--end .panel -->
			</div><!--end .panel-group -->
		</div>
		</div>
	</div>
	</div><!--end .col -->
</div><!--end .row -->
<!-- END COLORS -->

</div>
<script>
  $( function() {
    $( "#sortable" ).sortable({
		items: "li:not(.ui-state-disabled)",
		cancel: ".ui-state-disabled",
		axis: 'y',
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			console.log('data',data);
			// POST to server using $.post or $.ajax
			 $.ajax({
				data: data,
				type: 'POST',
				url: '<?=Url::to(['unit/sort'])?>'
			}); 
		}
	});
    $( "#sortable" ).disableSelection();
  } );
$(document).ready(function(){
	<!---------- validate unit title ------------>
	$('.unit_view').click(function(){
		//window.location.replace("<?=Url::to(['unit/update'])?>?id="+$(this).attr('data-unit_id'));
		if($(this).attr('data-unit_id') == 'new')
			var url = "<?=Url::to(['unit/create','m_id'=>$model->module_id])?>";
		else var url = "<?=Url::to(['unit/update'])?>?id="+$(this).attr('data-unit_id');
		$(location).attr('href',url);
	});
	<!---------- validate unit title ------------>
	<!---------- validate unit title ------------>
	$('#unit_title').on("blur",function(){
		var unit_title = $(this).val();
		if(!unit_title || unit_title == ''){
			$('.field-unit-title .help-block').html('Title cannot be blank');
			$('.field-unit-title').addClass('has-error');
			$('.field-unit-title').removeClass('has-success');
		}else{
			$('.field-unit-title .help-block').html('');
			$('.field-unit-title').addClass('has-success');	
			$('.field-unit-title').removeClass('has-error');			
		}
	});
	<!---------- validate unit title ------------>
	
	<!--------------unit elements-------------->
	var unit_element_options = {
		disableFields: ['autocomplete','button','checkbox','textarea','checkbox-group','hidden','select','header','date','number','radio-group','paragraph','text','fileupload'],
		fieldRemoveWarn: true,
		controlPosition: 'left',
	};
	var unit_element_editor = $(document.getElementById('build-wrap'));
 	var formData = '<?= $formdata ?>';
	//formData.replace(/"/g,"\\\"");
	//console.log(formData);
	if (formData) {
		unit_element_options.formData = formData;
	}	 
	$(unit_element_editor).formBuilder(unit_element_options);
	var saveBtn = document.querySelector('#frmb-0-save');
	saveBtn.onclick = function() {
		var unit_status = 0;
		if($('#unit_status').is(":checked"))
			unit_status = 1;
		var unit_title = $('#unit_title').val();
		if(!unit_title || unit_title == ''){
			$('.field-unit-title .help-block').html('Title cannot be blank');
			$('.field-unit-title').addClass('has-error');
			return false;
		}
		//console.log($(unit_element_editor).data('formBuilder').formData);
		var builder_data = JSON.stringify({'html':$(unit_element_editor).data('formBuilder').formData});
		//save to db
		$.ajax({
			url:'<?=Url::to(['unit/update','id'=>$model->unit_id])?>',
			data: {unit_title:unit_title,builder_data : builder_data,unit_status:unit_status},
			type: 'post',
			dataType : 'json',
			success : function(data){
				console.log(data);
			}
		});
		//console.log($(unit_element_editor).data('formBuilder').formData);
		//window.sessionStorage.setItem('formData', $(unit_element_editor).data('formBuilder').formData);
	};
	<!----------end of unit elements----------->
	<!---------- start of awareness elements ------->
	var awareness_elements = {
	  disableFields: ['autocomplete','button','checkbox','textarea','hidden','header','date','number','select','img','video','audio','paragraph','textdisplay','filedownload'],
	   fieldRemoveWarn: true ,
	   controlPosition: 'left',
	   editOnAdd: true,
	};
 	var aw_data = '<?=$aw_data?>';
	//aw_data.replace(/"/g,"&quot;");
	//var aw_data = aw_data.replace(/&amp;/g, '&');
	console.log(aw_data);
	if (aw_data) {
		awareness_elements.formData = aw_data;
	}	
	var awareness_editor = $(document.getElementById('aware_form'));
	$(awareness_editor).formBuilder(awareness_elements);
	//$('#aware_form').formBuilder(awareness_elements);
	//SaveAwarenessTest
	var saveBtn = document.querySelector('#frmb-2-save');
	saveBtn.onclick = function() {
		var form_data = $(awareness_editor).data('formBuilder').formData;
		var awareness_data = JSON.stringify({'html':form_data});
		//save to db
		$.ajax({
			url:'<?=Url::to(['unit/save-test','type'=>'aw'])?>',
			data: {unit_id:'<?=$model->unit_id?>',data : awareness_data},
			type: 'post',
			dataType : 'json',
			success : function(data){
				//console.log(data);
			}
		});	
	}
	<!---------- end of awareness elements ------->
	var cap_elements = {
	  disableFields: ['autocomplete','button','checkbox','textarea','checkbox-group','hidden','select','header','date','number','file','paragraph','text','img','video','audio','textdisplay','fileupload','filedownload'],
	   fieldRemoveWarn: true,
	   controlPosition: 'left',
	   editOnAdd: true,
	};
 	var cp_data = '<?= $cp_data ?>';
	//console.log(aw_data);
	if (aw_data) {
		cap_elements.formData = cp_data;
	}	
	var cap_editor = document.getElementById('capability_form');
	$(cap_editor).formBuilder(cap_elements);
	var saveBtn = document.querySelector('#frmb-4-save');
	saveBtn.onclick = function() {
		var form_data = $(cap_editor).data('formBuilder').formData;
		var cap_data = JSON.stringify({'html':form_data});
		//save to db
		$.ajax({
			url:'<?=Url::to(['unit/save-test','type'=>'cp'])?>',
			data: {unit_id:'<?=$model->unit_id?>',data : cap_data},
			type: 'post',
			dataType : 'json',
			success : function(data){
				//console.log(data);
			}
		});	
	}
});
<!---------- Save file -------------------->
function saveFile(input){
	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
	file = input.files[0];
	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
	if(file != undefined){
		waitingDialog.show('Uploading..');
	formData= new FormData();
	if(ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "mp4" || ext == "mp3" || ext == "pdf" || ext == "doc" || ext == "docx"){
		formData.append("media", file);
		$.ajax({
			url: "<?=Url::to(['unit/upload'])?>",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function(data){
				waitingDialog.hide();
				$(input).attr('src', data);
			}
		});
	}else{
		alert("Extension not supported");
		$(input).val("");
		return false;
	}


	}
}
//($('.fld-description').val()).length;
<!---------- End of save file ------------->
</script>