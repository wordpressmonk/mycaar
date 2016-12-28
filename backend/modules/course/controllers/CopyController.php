<?php

namespace backend\modules\course\controllers;

use Yii;
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
        $model = new Module();
		$program = Program::find()->where(["company_id"=>\Yii::$app->user->identity->c_id])->all();		
		 if ($model->load(Yii::$app->request->post())) {
				
				
				$program_id = $model->program_id;
				$copyprogram_id = $model->copy_program;
				$copymodule = Module::find()->where(["module_id"=>$model->copy_module,"program_id"=>$program_id])->one();
				
				$model2 = new Module();
				$model2->setAttributes($copymodule->getAttributes(), false);
				$model2->module_id = ""; 
				$model2->program_id = $copyprogram_id; 
				if($model2->save())
				{
					$model2->module_id;
					$copyunit = Unit::find()->where(["module_id"=>$model->copy_module])->all();
					
					foreach($copyunit as $tmpunit)
					{
						$unit = new Unit();
						$unit->setAttributes($tmpunit->getAttributes(), false);
						$unit->unit_id = ""; 
						$unit->module_id = $model2->module_id; 
						
						if($unit->save())
						{
							$copyunitelement = UnitElement::find()->where(["unit_id"=>$tmpunit->unit_id])->all();
							
							foreach($copyunitelement as $tmpunitelement)
							{
								$unitelement = new UnitElement();
								$unitelement->setAttributes($tmpunitelement->getAttributes(), false);
								$unitelement->element_id = ""; 
								$unitelement->unit_id = $unit->unit_id; 								
								$unitelement->save();								
							}
							
							$copycapabilityquestion = CapabilityQuestion::find()->where(["unit_id"=>$tmpunit->unit_id])->all();
							
							foreach($copycapabilityquestion as $tmpcapabilityquestion)
							{
								$capabilityquestion = new CapabilityQuestion();
								$capabilityquestion->setAttributes($tmpcapabilityquestion->getAttributes(), false);
								$capabilityquestion->cq_id = ""; 
								$capabilityquestion->unit_id = $unit->unit_id; 								
								$capabilityquestion->save();								
							}
							
							
					 		$copyawrenessquestion = AwarenessQuestion::find()->where(["unit_id"=>$tmpunit->unit_id])->all();
							
							foreach($copyawrenessquestion as $tmpawrenessquestion)
							{
								
								
								$awrenessquestion = new AwarenessQuestion();
								$awrenessquestion->setAttributes($tmpawrenessquestion->getAttributes(), false);
								$awrenessquestion->aq_id = ""; 							
								$awrenessquestion->unit_id = $unit->unit_id; 								
								$awrenessquestion->save();	
								 
								$options = explode("_",$tmpawrenessquestion->answer);
								
								foreach($options as $tmpoption)
								{
									
									$copyawrenessooption = AwarenessOption::find()->where(["question_id"=>$tmpoption])->one();
									
									$awrenessoption = new AwarenessOption();
									$awrenessoption->setAttributes($copyawrenessooption->getAttributes(), false);
									$awrenessoption->option_id = ""; 
									$awrenessoption->question_id = $awrenessquestion->aq_id;
									$awrenessoption->save(); 
									
								}  
								
							}
							
							
							
							 
						}
					}
					/* 
				
					echo "<pre>";
					print_r($copyunit);
					exit;
					 */
				}	
				
				
		 }
		return $this->render('copymodule', ['program' => $program,'model'=>$model]);
		
    }
	
	
	 public function actionGetModules()
    {
		echo "<option value=''>--Select the Module--</option>";
		
      if($post=Yii::$app->request->post())
	  {
		  $module = Module::find()->where(["program_id"=>$post['program_id']])->all();
		  if($module)
		  {
			  foreach($module as $tmp)
			  {
				  echo "<option value=".$tmp->module_id.">".$tmp->title."</option>";
			  }
		  }	
	  }
    }

}
