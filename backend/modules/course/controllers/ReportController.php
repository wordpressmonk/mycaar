<?php

namespace backend\modules\course\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\models\Report;
use common\models\search\SearchReport;
use common\models\Program;
use common\models\search\SearchProgram;
use common\models\UserProfile;

use common\models\Module;
use common\models\search\SearchModule;

use common\models\Unit;
use common\models\search\SearchUnit;

use common\models\UnitReport;
use common\models\search\SearchUnitReport;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class ReportController extends Controller
{
     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

             'access' => [
                'class' => AccessControl::className(),
				'only' => ['search','reset-programs','reset-modules','reset-units','reset-users'],
                'rules' => [
                    [
                        'actions' => ['search','assessor-report'],
                        'allow' => true,
						'roles' => ['assessor']
                    ],
                    [
                        'actions' => ['reset-programs','reset-modules','reset-units','reset-users'],
                        'allow' => true,
						'roles' => ['company_admin']
                    ],
                ],
            ], 
        ];
    }   

	public function actionSearch(){		
		$programs = $users = [];
		if($param = \Yii::$app->request->post()){	
			//find program
			if(isset($param['program']) && $param['program'] !=''){
				
				$programs[] = Program::find()->where(['program_id'=>$param['program']])->one();;
			}else{
				$programs = Program::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all();
			}
			$query = UserProfile::find();
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);	
			$query->joinWith(['user']);
			$query->andFilterWhere(['user.c_id'=>\Yii::$app->user->identity->c_id]);			
			//if any of the user parametr is filled,then search for that users
			//$query = User::find()->where(['c_id' =>Yii::$app->user->identity->c_id]);			
 			if(isset($param['user']) && $param['user'] !='')
				$query->andFilterWhere(['user_id'=>$param['user']]);			
 			if(isset($param['state']) && $param['state'] !='')
				$query->andFilterWhere(['state'=>$param['state']]);
			if(isset($param['role']) && $param['role'] !='')
				$query->andFilterWhere(['role'=>$param['role']]);
			if(isset($param['location']) && $param['location'] !='')
				$query->andFilterWhere(['location'=>$param['location']]);
			if(isset($param['division']) && $param['division'] !='')
				$query->andFilterWhere(['division'=>$param['division']]); 
			if(isset($param['firstname']) && $param['firstname'] !='')
				$query->andFilterWhere(['like', 'firstname',$param['firstname']]);
			if(isset($param['lastname']) && $param['lastname'] !='')
				$query->andFilterWhere(['like', 'lastname', $param['lastname']]);	
			
			$users = $dataProvider->models;
			//print_r($programs);
			return $this->render('report', [
				'programs' => $programs,
				'users' => $users,
				'params' => $param
			]);
		}
		
		/* $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]); */		
        else {
			$programs = Program::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all();
			$query = UserProfile::find();
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);	
			$query->joinWith(['user']);
			$query->andFilterWhere(['user.c_id'=>\Yii::$app->user->identity->c_id]);
			$users = $dataProvider->models;			
			
		return $this->render('report', [
					'programs' => $programs,
					'users' => $users,
					'params' => false,
				]);				
		}	
	}
	public function actionAssessorReport(){
        $searchModel = new SearchUnitReport();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
	public function actionResetPrograms(){
		
        $searchModel = new SearchProgram();
        $dataProvider = $searchModel->searchCompanyPrograms(Yii::$app->request->queryParams);
		if($post = \Yii::$app->request->post()){
			foreach($post['selection'] as $program){
				Program::findOne($program)->resetProgram();
			}
			return $this->redirect('reset-programs');
		}
        else return $this->render('reset_programs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
	public function actionResetModules($p_id=null){

		
        $searchModel = new SearchModule();
		if($p_id)
			$dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams,$p_id);
		else $dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams);
		
		if($post = \Yii::$app->request->post()){
			foreach($post['selection'] as $module){
				Module::findOne($module)->resetModule();
			}
			return $this->redirect(['reset-modules','p_id'=>$p_id]);
		}
        else return $this->render('reset_modules', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'p_id' => $p_id,
        ]);				
		
		
	}
	public function actionResetUnits($m_id=null){
		$searchModel = new SearchUnit();	
		if($m_id){
			$module = Module::findOne($m_id);
			if($module == null)
				throw new NotFoundHttpException('The requested page does not exist.');			
			$dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams,$m_id);
			$p_id = $module->program_id;
		}
			
		else {
			$dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams);
			$p_id = null;
		}
        
		
		if($post = \Yii::$app->request->post()){
			foreach($post['selection'] as $unit){
				Unit::findOne($unit)->resetUnit();
			}
			return $this->redirect(['reset-units','m_id'=>$m_id]);
		}
        else return $this->render('reset_units', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'm_id' => $m_id,
			'p_id' => $p_id,
        ]);					
	}
	public function actionResetUsers(){
		$searchModel = new SearchUnitReport();
/* 		if($u_id){
			$unit = Unit::findOne($u_id);
			if($unit == null)
				throw new NotFoundHttpException('The requested page does not exist.');
			$m_id = $unit->module->module_id;
			$p_id = $unit->module->program_id;
			
			if(isset(\Yii::$app->request->post()['custom_search'])){
				$params = \Yii::$app->request->post()['custom_search'];		
				$dataProvider = $searchModel->searchCustom($params,$u_id);
			}
			else $dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams,$u_id);
		}
		else
		{ */
			$params = false;
			if(isset(\Yii::$app->request->post()['custom_search'])){
				$params = \Yii::$app->request->post()['custom_search'];	
				//print_r($params);die;
				$dataProvider = $searchModel->searchCustom($params);				
			}
			else $dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams);
		//}   
		

		
			if(\Yii::$app->request->post() && isset(\Yii::$app->request->post()['selection'])){
				$post = \Yii::$app->request->post();
				foreach($post['selection'] as $report){
					$rep = UnitReport::findOne($report);
					if($rep != null){
						$rep->resetUser();
						$rep->save();
						//$rep->delete();				
					}
				}
				return $this->redirect(['reset-users']);
			}
			else return $this->render('reset_users', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'params' => $params
			]);				
		
	}
	public function actionResetTest($type,$r_id){
		
		$rep = UnitReport::findOne($r_id);
		if($rep == null)
			throw new NotFoundHttpException('The requested page does not exist.');
		switch($type){			
			case("cp"):
				$rep->resetCpTest();
				$rep->capability_progress = NULL;
				break;
			case("aw"):
				//delete all aware questions
				$rep->resetAwTest();			
				$rep->awareness_progress = NULL;
				break;
		}
		$rep->save(false);
		return $this->redirect(['report/reset-users','u_id'=>$rep->unit_id]);
	}
	public function actionGetModules($p_id){
		$mods = Module::find()->where(['program_id'=>$p_id])->all();
		 if(count($mods)>0){
			echo "<option value=''>--Select Course--</option>";
			foreach($mods as $mod){
				echo "<option value='".$mod->module_id."'>".$mod->title."</option>";
			}
		}
		else{
			echo "<option value=''>-</option>";
		}
	}
	
	public function actionGetUnits($m_id){
		$mods = Unit::find()->where(['module_id'=>$m_id])->all();
		 if(count($mods)>0){
			echo "<option value=''>--Select Lesson--</option>";
			foreach($mods as $mod){
				echo "<option value='".$mod->unit_id."'>".$mod->title."</option>";
			}
		}
		else{
			echo "<option value=''>-</option>";
		}
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