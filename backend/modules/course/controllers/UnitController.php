<?php

namespace backend\modules\course\controllers;

use Yii;
use common\models\Module;
use common\models\AwarenessQuestion;
use common\models\CapabilityQuestion;
use common\models\AwarenessOption;
use common\models\Unit;
use common\models\UnitElement;
use common\models\search\SearchUnit;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class UnitController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Unit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUnit();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Unit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Unit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($m_id)
    {
        $model = new Unit();
		$module = Module::find()->where(['module_id'=>$m_id])->one();
		if(!$module){
			return false;
		}
        if(isset(Yii::$app->request->post()['unit_title'])) {
			//print_r(Yii::$app->request->post());
			//$data = json_decode(Yii::$app->request->post()['builder_data']);
			//json_decode(stripslashes($_POST['posts']));
			//print_r($data->html);
			$model->module_id = $m_id;
			$model->title = Yii::$app->request->post()['unit_title'];
			$model->status = Yii::$app->request->post()['unit_status'];
			if($model->save()){
				//save elements as well
				$element = new UnitElement();
				$element->unit_id = $model->unit_id;
				$element->element_type = "page";
				$element->element_order = 1;
				$element->content = Yii::$app->request->post()['builder_data'];
				$element->save();
				//aw_dat
				$element = new UnitElement();
				$element->unit_id = $model->unit_id;
				$element->element_type = "aw_data";
				$element->element_order = 1;
				$element->content ='';
				$element->save();
				//cp_dat
				$element = new UnitElement();
				$element->unit_id = $model->unit_id;
				$element->element_type = "cap_data";
				$element->element_order = 1;
				$element->content ='';
				$element->save();
			}			
            return $this->redirect(['update', 'id' => $model->unit_id]);
        } else {
            return $this->render('add_unit', [
                'model' => $model,
				'module' => $module,
            ]);
        }
    }

    /**
     * Updates an existing Unit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$module = Module::find()->where(['module_id'=>$model->module_id])->one();
		
        if(isset(Yii::$app->request->post()['unit_title'])) {
			//print_r(Yii::$app->request->post());
			//$data = json_decode(Yii::$app->request->post()['builder_data']);
			//json_decode(stripslashes($_POST['posts']));
			//print_r($data->html);
			//$model->module_id = $m_id;
			$model->title = Yii::$app->request->post()['unit_title'];
			$model->status = Yii::$app->request->post()['unit_status'];
			if($model->save()){
				//save elements as well
				$element = UnitElement::find()->where(['unit_id'=>$id,'element_type'=>'page'])->one();
				$element->unit_id = $model->unit_id;
				$element->element_type = "page";
				$element->element_order = 1;
				$element->content = Yii::$app->request->post()['builder_data'];
				$element->save();
			}
            return $this->redirect(['update', 'id' => $model->unit_id]);
        } else {
            return $this->render('update_unit', [
                'model' => $model,
				'module' => $module,
            ]);
        }
    }

    /**
     * Deletes an existing Unit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Unit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Unit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
	 * Save the media to the uploads folder
	 * Return the path
	 */
	public function actionUpload(){
		$dir = "uploads/media/";
		move_uploaded_file($_FILES["media"]["tmp_name"], $dir. $_FILES["media"]["name"]);
		return \Yii::$app->homeUrl.$dir. $_FILES["media"]["name"];
	}
	
	/**
	 * Save the tests actionCreate
	 * Parms: type- aw or cp 
	 * Reload the update page after save
	 */
	public function actionSaveTest($type){
		$output = [];
		$data = json_decode(Yii::$app->request->post()['data']);		
		$questions = $this->formatQuestions($data->html);
		if($type == "aw")
			$this->saveQuestions(Yii::$app->request->post()['unit_id'],$questions);
		if($type == "cp")
			$this->saveCapQuestions(Yii::$app->request->post()['unit_id'],$questions);
		return $this->redirect(['update', 'id' => Yii::$app->request->post()['unit_id']]);
	}
	
	/**
	 * Format questios array from string to array
	 * Parms: $data - formdata returned by formbuilder
	 * Returns array
	 */	
	public function formatQuestions($data){
		$output = [];
		$html = str_replace("<fields>","",$data);
		$html = str_replace("</fields>","",$html);
		$questions = [];
		preg_match_all('/<field[^>]*?>([\s\S]*?)<\/field>/', $html,$questions, PREG_SET_ORDER);
		foreach($questions as $question){
			$field_reg = $question[0];
			$options_reg = $question[1];
			$data = [
				'question' => [],
				'type' => [],
				'options' => [],				
				'id' => [],
				'answer' => [],
				'description' => [],
			];
		preg_match_all('/<option[^>]*?>([\s\S]*?)<\/option>/', $options_reg,$data['options'], PREG_SET_ORDER);
		foreach($data['options'] as $key=>$dat){
			preg_match_all('/option_id="([\s\S]*?)"/', $dat[0],$data['options'][$key][2], PREG_SET_ORDER);
		}
		preg_match_all('/type="([\s\S]*?)"/', $field_reg,$data['type'], PREG_SET_ORDER);
		preg_match_all('/description="([\s\S]*?)"/', $field_reg,$data['description'], PREG_SET_ORDER);
		preg_match_all('/[^<option] label="([\s\S]*?)"/', $field_reg,$data['question'], PREG_SET_ORDER);
		preg_match_all('/name="([\s\S]*?)"/', $field_reg,$data['id'], PREG_SET_ORDER);
		preg_match_all('/selected="true">([\s\S]*?)</', $options_reg,$data['answer'], PREG_SET_ORDER);
		$output[] = $data;
	
		}
		return $output;		
	}
	
	/**
	 * Save awareness questions and options
	 * Parms: unit_id , questions array 
	 * Return null
	 */	
	public function saveQuestions($unit_id,$questions){
		//see if any questions deleted
		$from_update = $to_update = [];
		$current_qstns = Unit::findOne($unit_id)->awarenessQuestions;
		foreach($current_qstns as $q){
			$from_update[] = $q->aq_id;
		}
		$html  = '<form-template><fields>';			
		foreach($questions as $order => $quest){		
			$id = $quest['id'][0][1];
			$id = preg_replace("/[^0-9]/","",$id);//die;
			$awareness_question = AwarenessQuestion::find()->where(['aq_id'=>$id,'unit_id'=>$unit_id])->one();			
			if(!$awareness_question){
				$awareness_question = new AwarenessQuestion();
				$awareness_question->unit_id = $unit_id;
			}else{
				//get the existing questions list
				$to_update[] = $id;
			}
			$awareness_question->question = $quest['question'][0][1];
			$awareness_question->question_type = $quest['type'][0][1];
			$awareness_question->order_id = $order;
			$answer = "";
			$description = $awareness_question->description  = '';
			if(isset($quest['description'][0][1]))
					$awareness_question->description  = $quest['description'][0][1];
			if($awareness_question->save(false)){
				//reformat the form data
				$name = $quest['type'][0][1]."-".$awareness_question->aq_id; //change this to primary key
				$type = $class = $quest['type'][0][1];
				$label = $quest['question'][0][1];
				if(isset($quest['description'][0][1]))
					$description = $quest['description'][0][1];
				$html .= '<field type="'.$type.'" description="'.$description.'" label="'.$label.'" class="'.$class.'" name="'.$name.'" src="false">';					
				//////////////
				if(!empty($quest['options'])){
					foreach($quest['options'] as $opt){
						$option_id = $opt[2][0][1];
						$awareness_option = AwarenessOption::findOne($option_id);
						if (!$awareness_option) 
							$awareness_option = new AwarenessOption();
						$awareness_option->question_id =  $awareness_question->aq_id;
						$awareness_option->answer = $opt[1];
						$awareness_option->save();
						$opt_string = '<option option_id="'.$awareness_option->option_id.'" label="'.$opt[1].'" value="'.$opt[1].'">'.$opt[1].'</option>';
							if(!empty($quest['answer'])){
								foreach($quest['answer'] as $ans){
									if($ans[1] === $awareness_option->answer){
										$answer .= $awareness_option->option_id."_";
										$opt_string = '<option option_id="'.$awareness_option->option_id.'" label="'.$opt[1].'" value="'.$opt[1].'" selected="true">'.$opt[1].'</option>';
									}
									 
								}
							}
						$html .= $opt_string;
					}
				}
				$html .= '</field>';
				$awareness_question->answer = $answer;
				$awareness_question->save();
				//return true;
				
			}
		}
		$html  .= '</fields></form-template>';
		$element = UnitElement::find()->where(['unit_id'=>$unit_id,'element_type'=>'aw_data'])->one();
		if(!$element)
			$element = new UnitElement();
		
		$element->unit_id = $unit_id;
		$element->element_type = "aw_data";
		$element->element_order = 1;
		$element->content =$html;
		$element->save();
		//delete the elements
		$deleted=array_diff($from_update,$to_update);
		foreach($deleted as $del){
			AwarenessQuestion::findOne($del)->delete();
		}
	}	
	
	/**
	 * Save capability questions
	 * No options are saved here
	 * Parms: unit_id , questions array 
	 * Return null
	 */	
	public function saveCapQuestions($unit_id,$questions){
		//see if any questions deleted
		$from_update = $to_update = [];
		$current_qstns = Unit::findOne($unit_id)->capabilityQuestions;
		foreach($current_qstns as $q){
			$from_update[] = $q->cq_id;
		}
		$html  = '<form-template><fields>';			
		foreach($questions as $order => $quest){		
			$id = $quest['id'][0][1];
			$id = preg_replace("/[^0-9]/","",$id);//die;
			$cap_question = CapabilityQuestion::find()->where(['cq_id'=>$id,'unit_id'=>$unit_id])->one();			
			if(!$cap_question){
				$cap_question = new CapabilityQuestion();
				$cap_question->unit_id = $unit_id;
			}else{
				//get the existing questions list
				$to_update[] = $id;
			}
			$cap_question->question = $quest['question'][0][1];
			//$cap_question->question_type = $quest['type'][0][1];
			$cap_question->order_id = $order;
			$answer = "";
			$description = $cap_question->description  = '';
			if(isset($quest['description'][0][1]))
					$cap_question->description  = $quest['description'][0][1];
			if($cap_question->save(false)){
				//reformat the form data
				$name = $quest['type'][0][1]."-".$cap_question->cq_id; //change this to primary key
				$type = $class = $quest['type'][0][1];
				$label = $quest['question'][0][1];
				if(isset($quest['description'][0][1]))
					$description = $quest['description'][0][1];
				$html .= '<field type="'.$type.'" description="'.$description.'" label="'.$label.'" class="'.$class.'" name="'.$name.'" src="false">';					
				//////////////
				if(!empty($quest['options'])){
					foreach($quest['options'] as $opt){
						$opt_string = '<option  label="'.$opt[1].'" value="'.$opt[1].'">'.$opt[1].'</option>';
							if(!empty($quest['answer'])){
								foreach($quest['answer'] as $ans){
									if($ans[1] === $opt[1]){
										$answer = $opt[1];
										$opt_string = '<option label="'.$opt[1].'" value="'.$opt[1].'" selected="true">'.$opt[1].'</option>';
									}
									 
								}
							}
						$html .= $opt_string;
					}
				}
				$html .= '</field>';
				$cap_question->answer = $answer;
				$cap_question->save();
				//return true;
				
			}
		}
		$html  .= '</fields></form-template>';
		$element = UnitElement::find()->where(['unit_id'=>$unit_id,'element_type'=>'cap_data'])->one();
		if(!$element)
			$element = new UnitElement();
		
		$element->unit_id = $unit_id;
		$element->element_type = "cap_data";
		$element->element_order = 1;
		$element->content =$html;
		$element->save();
		//delete the elements
		$deleted=array_diff($from_update,$to_update);
		foreach($deleted as $del){
			CapabilityQuestion::findOne($del)->delete();
		}		
		
	}
}
