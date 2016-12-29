<?php

namespace backend\modules\course\controllers;

use Yii;
use common\models\CopyModule;
use common\models\Module;
use common\models\Unit;
use common\models\UnitElement;
use common\models\AwarenessQuestion;
use common\models\AwarenessOption;
use common\models\CapabilityQuestion;
use common\models\Program;

class CopyController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new CopyModule();
		$program = Program::find()->where(["company_id"=>\Yii::$app->user->identity->c_id])->orderBy("title")->all();		
		 if ($model->load(Yii::$app->request->post())) {
								
				$program_id = $model->program_id;
				$copyprogram_id = $model->copy_program;
				$copymodule = Module::find()->where(["module_id"=>$model->copy_module,"program_id"=>$program_id])->one();
				$count = Module::find()->where(["program_id"=>$copyprogram_id])->count();
				
			if($copymodule)
			{
				$module = new Module();
				$module->setAttributes($copymodule->getAttributes(), false);
				$module->module_id = ""; 
				$module->module_order = $count; 
				$module->program_id = $copyprogram_id; 
				if($module->save())
				{
					$module->module_id;
					$copyunit = Unit::find()->where(["module_id"=>$model->copy_module])->all();
					
				 if($copyunit)
				  {
					foreach($copyunit as $tmpunit)
					{
						$unit = new Unit();
						$unit->setAttributes($tmpunit->getAttributes(), false);
						$unit->unit_id = ""; 
						$unit->module_id = $module->module_id; 
						
						if($unit->save())
						{
							$copyunitelement = UnitElement::find()->where(["unit_id"=>$tmpunit->unit_id])->all();
							
						  if($copyunitelement)
						  {
							foreach($copyunitelement as $tmpunitelement)
							{
								$unitelement = new UnitElement();
								$unitelement->setAttributes($tmpunitelement->getAttributes(), false);
								$unitelement->element_id = ""; 
								$unitelement->unit_id = $unit->unit_id; 								
								$unitelement->save();								
							}
							
						  }	
							$copycapabilityquestion = CapabilityQuestion::find()->where(["unit_id"=>$tmpunit->unit_id])->all();
						 
						 if($copycapabilityquestion)
						  {
							foreach($copycapabilityquestion as $tmpcapabilityquestion)
							{
								$capabilityquestion = new CapabilityQuestion();
								$capabilityquestion->setAttributes($tmpcapabilityquestion->getAttributes(), false);
								$capabilityquestion->cq_id = ""; 
								$capabilityquestion->unit_id = $unit->unit_id; 								
								$capabilityquestion->save();								
							}
						  }
							
					 	 $copyawrenessquestion = AwarenessQuestion::find()->where(["unit_id"=>$tmpunit->unit_id])->all();
						  if($copyawrenessquestion)
						  {
							foreach($copyawrenessquestion as $tmpawrenessquestion)
							{																
								$awrenessquestion = new AwarenessQuestion();
								$awrenessquestion->setAttributes($tmpawrenessquestion->getAttributes(), false);
								$awrenessquestion->aq_id = ""; 							
								$awrenessquestion->unit_id = $unit->unit_id; 								
								$awrenessquestion->answer = ""; 								
								$awrenessquestion->save(false);	
								unset($optionsvalues); 
								$options = explode("_",$tmpawrenessquestion->answer);
								$optionsvalues = [];
								
							 if($options)
							 {		
								foreach($options as $tmpoption)
								{
								  $copyawrenessooption = AwarenessOption::find()->where(["question_id"=>$tmpoption])->one();
								  if($copyawrenessooption)
							      {
									$awrenessoption = new AwarenessOption();
									$awrenessoption->setAttributes($copyawrenessooption->getAttributes(), false);
									$awrenessoption->option_id = ""; 
									$awrenessoption->question_id = $awrenessquestion->aq_id;
									$awrenessoption->save();
									
									$optionsvalues[] = $awrenessoption->option_id; 
								  }
								}  
							 }
								$answer = implode("_",$optionsvalues);
								$awrenessquestion->answer = $answer; 								
								$awrenessquestion->save();
							 	
							}
						  } 
						}
					}
				  }		
				}	
				
			  Yii::$app->getSession()->setFlash('Success', 'Selected module has been successfully copied !!!.');
			}
		 }
		return $this->render('copy', ['program' => $program,'model'=>$model]);
		
    }
	
	
	 public function actionGetModules()
    {
		echo "<option value=''>--Select Module--</option>";
		
      if($post=Yii::$app->request->post())
	  {
		  $module = Module::find()->where(["program_id"=>$post['program_id']])->orderBy("title")->all();
		  if($module)
		  {
			  foreach($module as $tmp)
			  {
				  echo "<option value=".$tmp->module_id.">".$tmp->title."</option>";
			  }
		  }	
	  }
    }
	
	public function actionGetModulesSelected()
    {
		echo "<option value=''>--Select Module--</option>";
		
      if($post=Yii::$app->request->post())
	  {
		  $module = Module::find()->where(["program_id"=>$post['program_id']])->orderBy("title")->all();
		  if($module)
		  {
			  foreach($module as $tmp)
			  {
				  if(isset($post['module_id']) && ( $post['module_id'] == $tmp->module_id))
				    echo "<option selected='selected' value=".$tmp->module_id.">".$tmp->title."</option>";
				  else 
				    echo "<option value=".$tmp->module_id.">".$tmp->title."</option>";
			  }
		  }	
	  }
    }

}
