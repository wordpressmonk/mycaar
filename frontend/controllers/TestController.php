<?php

namespace frontend\controllers;

use Yii;
use common\models\Module;
use common\models\AwarenessAnswer;
use common\models\AwarenessQuestion;
use common\models\CapabilityQuestion;
use common\models\AwarenessOption;
use common\models\Unit;
use common\models\UnitElement;
use common\models\search\SearchUnit;
use common\models\UnitReport as Report;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class TestController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['learn', 'aw-test', 'retake'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }
	public function actionLearn($u_id){
        return $this->render('view', [
            'model' => $this->findModel($u_id),
        ]);		
	}
    /**
     * Lists all Unit models.
     * @return mixed
     */
    public function actionAwTest($u_id)
    {	
		$current_unit = Unit::findOne($u_id);
		if($current_unit == null)
			throw new NotFoundHttpException('The requested page does not exist.');
		$previous_unit = Unit::find()->where(['and', "unit_id<$u_id", "module_id=$current_unit->module_id"])->orderBy('unit_id DESC')->one();
		//

		if($previous_unit){
			//print_r($current_unit);die;
			$previous_unit_report = Report::find()->where(['unit_id'=>$previous_unit->unit_id,'student_id'=>\Yii::$app->user->id])->one();
			if($previous_unit->unit_id != $u_id && (!$previous_unit_report || $previous_unit_report->awareness_progress != 100)){
				\Yii::$app->getSession()->setFlash('error', 'Please complete the previous unit/s');
				return $this->redirect(['site/index']);
			}
							
		}

		//if previous unit is completed
		$session = Yii::$app->session;
		$attempted = Report::find()->where(['unit_id'=>$u_id,'student_id'=>\Yii::$app->user->id])->one();
		if($attempted && !isset($session[$u_id."_".\Yii::$app->user->id]))
			return $this->redirect(['retake','u_id'=>$u_id]); 
		//
		
		//initialise here
		
		//$session->remove($u_id."_".\Yii::$app->user->id);die;
		if(isset($session[$u_id."_".\Yii::$app->user->id])){
			$page = $session[$u_id."_".\Yii::$app->user->id];
		}			
		else $page = $session[$u_id."_".\Yii::$app->user->id] = 0;
		/////////////////////////
		//die;
		$limit = 2;
		$offset = $page*$limit;
		$model = $this->findModel($u_id);
		$final = false;
		$total = AwarenessQuestion::find()->where('unit_id = :unit_id', [':unit_id' => $u_id])->count();
/* 		if($total <= $offset)
			$final = true;
			if($final){
				$session->remove($u_id."_".\Yii::$app->user->id);
				return $this->redirect(["site/index"]);
			} */
		if($total <= ($page+1)*$limit)
			$final = true;
		$questions = AwarenessQuestion::find()->where('unit_id = :unit_id', [':unit_id' => $u_id])->orderBy('order_id ASC')->limit($limit)->offset($offset)->all();
		//print_r($questions);
		foreach($questions as $key=>$quest){
			$questions[$key]['options'] = $quest->awarenessOptions;
		}
		if($answers = Yii::$app->request->post()){
			//print_r(Yii::$app->request->post());die;
			$count_qstns = count($questions);
			$ans_qstns = count($answers)-1;
			if( in_array("", $answers, true) || $count_qstns > $ans_qstns){
				return $this->render('test', [
					'model' => $model,
					'questions' => $questions,
					'answers' => json_encode($answers),
					'errors' => true,
					'final'=> $final,
				]);				
			}
			$this->saveAnswers($answers);
			$session[$u_id."_".\Yii::$app->user->id] = $page+1;
			//save answers
			//print_r($answers);
			$this->saveProgress(\Yii::$app->user->id,$u_id);
			//redirect to next page or homepage
			if(isset(Yii::$app->request->post()['save_n_exit'])){
				if(Yii::$app->request->post()['save_n_exit'] == 'Finish'){
					$session->remove($u_id."_".\Yii::$app->user->id);
					return $this->redirect(["site/index"]);
				}
				if(Yii::$app->request->post()['save_n_exit'] == 'Save&Exit'){
					//$session->remove($u_id."_".\Yii::$app->user->id);
					return $this->redirect(["site/index"]);
				}					
			}
			
			return $this->redirect(["aw-test","u_id"=>$u_id]);
		}
		
		else return $this->render('test', [
            'model' => $model,
			'questions' => $questions,
			'answers' => false,
			'errors' => false,
			'final'=> $final,
        ]);
    }
	
	public function actionRetake($u_id)
	{
		//see if any new questions are added to the unit here
		
/* 		$this->isAnyChange($u_id,\Yii::$app->user->id);
		die; */
		$model = $this->findModel($u_id);
		$questions = $model->awarenessQuestions;
		foreach($questions as $key=>$quest){
			$questions[$key]['options'] = $quest->awarenessOptions;
			$questions[$key]['answers'] =  AwarenessAnswer::find()->where(['user_id'=>\Yii::$app->user->id,'question_id'=>$quest->aq_id])->asArray()->one();
			$questions[$key]['isCorrect']  = false;
			if($questions[$key]['answers']['answer'] == $quest->answer){
				$questions[$key]['isCorrect']  = true;
			}
				
		}	
		//print_r($questions);die;
		if($answers = Yii::$app->request->post()){
			$this->saveAnswers($answers);
			if(isset(Yii::$app->request->post()['save_n_exit']))
				return $this->redirect(["site/index"]);
			if($this->saveProgress(\Yii::$app->user->id,$u_id)!= 100)
				return $this->redirect(['retake','u_id'=>$u_id]);
			//redirect to next page or homepage
			return $this->redirect(["site/index"]);
		}		
		else return $this->render('retest', [
            'model' => $model,
			'questions' => $questions,
			'answers' => false,
			'errors' => false,
        ]);		
	}

	public function saveAnswers($answers){
		foreach($answers as $question=>$answer){				
			$aw_answer = AwarenessAnswer::find()->where(['user_id'=>\Yii::$app->user->id,'question_id'=>$question])->one();
			if(!$aw_answer)
				$aw_answer = new AwarenessAnswer();
			
			$aw_answer->user_id = \Yii::$app->user->id;
			$aw_answer->question_id = $question;
			if(is_array($answer)){
				sort($answer);	
				$answer = implode('_',$answer);
			}			
			$aw_answer->answer = $answer;
			$aw_answer->save();				
		}		
	}
	public function isAnyChange($unit_id,$user_id){
		$query = new \yii\db\Query;
 		$query->select('question.aq_id , answer.question_id')
				->from('awareness_question question')
				->leftJoin('awareness_answer answer', 'answer.question_id = question.aq_id AND answer.user_id='.$user_id)  
				->where("question.unit_id=$unit_id"); 

		$command = $query->createCommand();
		$resp = $command->queryAll();
		print_r($resp);
		//foreach()	
	}
	public function saveProgress($user_id,$unit_id){
		/**
		SELECT count(question.aq_id) as questions, count(answer.aa_id) as right_answer FROM `awareness_question` as question 
		LEFT JOIN awareness_answer as answer
		on (answer.question_id = question.aq_id and answer.answer = question.answer)
		WHERE question.unit_id=1 AND (question.question_type = 'radio-group' OR question.question_type = 'checkbox-group')
		**/
		$query = new \yii\db\Query;
		$query->select('count(question.aq_id) as questions, count(answer.aa_id) as right_answer')
				->from('awareness_question question')
				->leftJoin('awareness_answer answer', 'answer.question_id = question.aq_id AND answer.answer = question.answer AND answer.user_id='.$user_id)  
				->where("question.unit_id=$unit_id AND (question.question_type = 'radio-group' OR question.question_type = 'checkbox-group')");
		$command = $query->createCommand();
		$resp = $command->queryOne();
		//print_R($resp);die;
		$progress = ($resp['right_answer']/$resp['questions'])*100;//die;
		//save progress to DB
		$report = Report::find()->where(['unit_id'=>$unit_id,'student_id'=>$user_id])->one();
		if(!$report)
			$report = new Report();
		$report->unit_id = $unit_id;
		$report->student_id = $user_id;
		$report->awareness_progress = $progress;
		$report->save();
		return (int)$progress;
		//print_R($resp);
		//get total answered by the user and validate the right ones
		
		//progress is right/total*100
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
	

}
