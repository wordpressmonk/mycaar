<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\UnitElement;
use yii\bootstrap\ActiveForm;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Units', 'url' => ['Test']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-head regular style-info">
	<header><?=$this->title?></header>
</div>
<div  class="card">
<div class="card-body">
	<form class="form" method="post">
	<?php 
	if($errors){
		echo '<div class="alert alert-danger" role="alert">
				<strong>Oh wait!</strong> All questions are mandatory.
			</div>';
	}
	foreach($questions as $question){
		echo '<div class="small-padding">';
		switch($question->question_type){
			//////RADIO GROUP//////
			case "radio-group":
				echo "<h3>{$question->question}</h3>";
				echo "<p>{$question->description}</p>";
				
				
				$disabled = "";
				if($question->isCorrect){
					$disabled = "disabled";				
				}else{
					echo '<div class="alert alert-callout alert-danger" role="alert">';
				}
				echo '<div class="form-group">';
				foreach($question->options as $option){
					$checked = "";
					if($question->answers['answer'] == $option->option_id)
						$checked = "checked";
					echo 
					"<div class='radio radio-styled radio-info'>
						<label>
						<input type='radio' name='$question->aq_id' value='{$option->option_id}' $checked $disabled>
						<span>{$option->answer}</span>
						</label>";
						if($question->answer == $option->option_id && $question->isCorrect)
							echo '<i class="pull-right glyphicon glyphicon-ok-sign text-success"></i>';
						if($checked == "checked" && !$question->isCorrect )
							echo '<i class="pull-right glyphicon glyphicon-remove-sign text-danger"></i>';
							
					echo"</div>";
				}
				//echo "<div class='text-danger' id='help_{$question->aq_id}'></div>";
				echo "</div>";
				if(!$question->isCorrect){
					echo "</div>";
				}
				break;
			/////////CHECKBOX GROUP//////
			case "checkbox-group":
				echo "<h3>{$question->question}</h3>";
				echo "<p>{$question->description}</p>";
				$disabled = "";
				if($question->isCorrect){
					$disabled = "disabled";			
				}else{
					echo '<div class="alert alert-callout alert-danger" role="alert">';
				}
				echo '<div class="form-group">';
				foreach($question->options as $option){
					$checked = "";
					$checkd_options = explode("_",$question->answers['answer']);
					$correct_answers = explode("_",$question->answer);
					if(in_array($option->option_id,$checkd_options))
						$checked = "checked";
					echo 
					"<div class='checkbox checkbox-styled checkbox-info'>
						<label>
						<input type='checkbox' data_q='{$question->aq_id}' name='{$question->aq_id}[]' value='{$option->option_id}' $checked $disabled>
						<span>{$option->answer}</span>
						</label>";
					if(in_array($option->option_id,$correct_answers) && $question->isCorrect && $checked == "checked")
							echo '<i class="pull-right glyphicon glyphicon-ok-sign text-success"></i>';
					else if(in_array($option->option_id,$correct_answers) && $checked == "checked")
							echo '<i class="pull-right glyphicon glyphicon-ok-sign text-success"></i>';
					else if(!in_array($option->option_id,$correct_answers) && $checked == "checked" && !$question->isCorrect )
						echo '<i class="pull-right glyphicon glyphicon-remove-sign text-danger"></i>';
					
					echo "</div>";
				}
				//echo "<div class='text-danger' id='help_{$question->aq_id}'></div>";
				echo '</div>';
				if(!$question->isCorrect){
					echo "</div>";
				}
				break;
			case "fileupload":
				echo "<h3>{$question->question}</h3>";
				echo "<p>{$question->description}</p>";
				echo '<div class="form-group">';
				echo 
				"<div class='border-gray small-padding form-group'>
					<input name='$question->aq_id' type='file' class='form-control' value='{$question->answers['answer']}'>
				</div>";
				//echo "<div class='text-danger' id='help_{$question->aq_id}'></div>";
				echo '</div>';
				break;
			case "text":
				echo "<h3>{$question->question}</h3>";
				echo "<p>{$question->description}</p>";
				echo '<div class="form-group">';
				echo
				"<div class='form-group'>
					<input name='$question->aq_id' type='text' class='form-control' placeholder='' value='{$question->answers['answer']}'>
					<label>Enter Answer here</label>
				</div>";
				//echo "<div class='text-danger' id='help_{$question->aq_id}'></div>";
				echo '</div>';
				break;				
		}
			
		echo '</div>';
	}
	?>
	<div class="form-group">
		<input name= "save" type="submit" class="btn btn-lg ink-reaction btn-info" value="Next"/>
		<input name= "save_n_exit" type="submit" class="btn btn-lg ink-reaction btn-info" value="Save&Exit"/>
	</div>
	</form>
</div>
</div>
<script>
	var answers = '<?=$answers ?>';
	if(answers){
		var answers = $.parseJSON(answers);
		//console.log(answers);
		$(answers).each(function(i,val){
			$.each(val,function(k,v){
				//console.log(k+" : "+ v);  
				if(v !== ''){
					var qstn = $("input[name="+k+"]") ;
					if(!qstn.length || qstn.length == 0)
						var qstn = $("input[data_q="+k+"]");
					//console.log(qstn);
					var type = qstn.attr("type");
					switch(type){
						case "checkbox":
							$.each(v, function(p,q){
								//console.log(p+" : "+ q);  
								var qstn = $("input[data_q="+k+"][value="+q+"]");
								qstn.attr("checked",true);
							});
							break;
						case "radio":
							var qstn = $("input[name="+k+"][value="+v+"]");
							qstn.attr("checked",true);
							break;
						case "file":
						case "text":
							qstn.val(v);
							break;
						
					}
				}else{
					$("#help_"+k).html("Please answer this question dude");
				}
			});
		});		
	}

</script>