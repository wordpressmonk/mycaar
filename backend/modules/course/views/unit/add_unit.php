<?php 
use yii\helpers\Url;

$this->registerCssFile(\Yii::$app->homeUrl."css/custom/form-builder.css");
$this->registerCssFile(\Yii::$app->homeUrl."css/custom/form-render.css");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/form-builder.js");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/form-render.js");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/waitingfor.js");
$this->registerJsFile(\Yii::$app->homeUrl."js/custom/jquery-ui.min.js");

?>
<style>
button#frmb-0-view-data,button#frmb-1-view-data,button#frmb-2-view-data{
	display:none;
}
.required-wrap{
	display:none;
}
</style>
<div class="section-body contain-lg">

<div class="row">
	<div class="col-lg-12">
		<h2>Add Lesson</h2>
	</div><!--end .col -->
	<div class="col-lg-12">
	<h4 class="small-padding">[ Program: <a href="<?= Url::to(['program/view','id'=>$module->program->program_id])?>"><?=$module->program->title?></a> , Module: <a href="<?= Url::to(['module/update','id'=>$module->module_id])?>"><?=$module->title?> ]</a></h4>
	<div class="card tabs-left style-default-light">
		<ul id="sortable" class="card-head nav nav-tabs tabs-info" data-toggle="tabs">
			<?php foreach($module->units as $unit){
/* 				if($unit->unit_id == $model->unit_id)
					echo '<li class="active"><a href="#tab1">'.$unit->title.'</a></li>';
				else */
					echo '<li id="unit_'.$unit->unit_id.'" class="ui-state-default"><a class="unit_view" data-unit_id="'.$unit->unit_id.'" href="#tab2">'.substr($unit->title,0,12).'..</a></li>';
			}?>
			<li class="ui-state-disabled active text-info small-padding"><h4>ADD NEW</h4></li>
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
					<div id="unit_details" class="collapse in" aria-expanded="true">
						<div class="card-body">
							
							<div class="checkbox checkbox-styled checkbox-info  pull-right">
								<label>
									<input id="unit_status" type="checkbox" value="" checked>
									<span>Publish</span>
								</label>
							</div>
							
							<div class="form-group field-unit-title required">
								<label>Lesson Title</label>
								<input type="text" id="unit_title" class="form-control">
								<div class="help-block"></div>
								
							</div>
							<div class="form-group field-reset-period">
								<label>Auto Reset Period [In months]</label>
								<input type="text" id="auto_reset_period" class="form-control">
								<div class="help-block"></div>
								
							</div>
							<div class="form-group">
								<div class="checkbox checkbox-styled">
									<label>
										<?php 
											if($model->show_learning_page )
												$checked = "checked";
											else $checked = "";
										?>
										<input id="show_learning_page" type="checkbox" <?=$checked?>>
										<span>Show Lesson Details Page</span>
									</label>
								</div>
							</div>
					
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
					<!--<div id="awareness_test" class="collapse" aria-expanded="false">
						<div class="card-body">
						<div id="aware_form"></div>
						</div>
					</div>-->
				</div><!--end .panel -->
				<br>
				<div class="card panel">
					<div class="card-head style-info collapsed" data-toggle="collapse" data-parent="#unit_accordian" data-target="#capability-test" aria-expanded="false">
						<header>Capability Test</header>
						<div class="tools">
							<a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
						</div>
					</div>
					<!--<div id="capability-test" class="collapse" aria-expanded="false">
						<div class="card-body">
						<div id="capability_form"></div>
						</div>
					</div>-->
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
/* 	var formData = window.sessionStorage.getItem('formData');
	if (formData) {
		unit_element_options.formData = formData;
	}	 */
	$(unit_element_editor).formBuilder(unit_element_options);
	var saveBtn = document.querySelector('#frmb-0-save');
	saveBtn.onclick = function() {
		var unit_status = 0;
		if($('#unit_status').is(":checked"))
			unit_status = 1;
		var show_learning_page = 0;
		if($('#show_learning_page').is(":checked"))
			show_learning_page = 1;	
		var auto_reset_period = $('#auto_reset_period').val();
		if(auto_reset_period.length > 2){
			$('.field-reset-period').addClass('has-error');
			$('.field-reset-period .help-block').html('Invalid reset period');
			return false;
		}
		var unit_title = $('#unit_title').val();
		if(!unit_title || unit_title == ''){
			$('.field-unit-title .help-block').html('Title cannot be blank');
			$('.field-unit-title').addClass('has-error');
			return false;
		}
		console.log($(unit_element_editor).data('formBuilder').formData);
		var builder_data = JSON.stringify({'html':$(unit_element_editor).data('formBuilder').formData});
		//save to db
		$.ajax({
			url:'<?=Url::to(['unit/create','m_id'=>$module->module_id])?>',
			data: {unit_title:unit_title,builder_data : builder_data,unit_status:unit_status,reset_period:auto_reset_period,show_learning_page:show_learning_page},
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
});
<!---------- Save file -------------------->
function saveFile(input){
/* 	console.log(new FormData(elem));
	$.ajax({
		url: "form.php",
		type: "POST",
		data: new FormData(elem),
		contentType: false,
		cache: false,
		processData: false,
		success: function (data) {
			$("#targetLayer").html(data);
		},
		error: function () {
		}
	});	 */	
	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
	//&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")
/* 	if (input.files && input.files[0] ) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$(input).attr('src', e.target.result);
			console.log(e.target.result);
		}
		//console.log(e.target.result);
		reader.readAsDataURL(input.files[0]);
	}else{
		 $('#img').attr('src', '/assets/no_preview.png');
	} */
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
		return false;
	}

    //if(!!file.type.match(/image.*/)){

    }
}
<!---------- End of save file ------------->
</script>