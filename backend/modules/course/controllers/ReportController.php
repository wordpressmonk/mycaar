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
use common\models\UserProfile;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class ReportController extends Controller
{
    
	/**
     * Lists all Program models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchReport();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionSearch(){
		
		//if user
		
		$programs = $users = [];
		
		if($param = \Yii::$app->request->post()){	
			//find program
			if(isset($param['program']) && $param['program'] !=''){
				$programs[] = $param['program'];
			}else{
				$programs = Program::find()->where(['company_id'=>\Yii::$app->user->identity->c_id])->all();
			}
			
			//if any of the user parametr is filled,then search for that users
			//$query = User::find()->where(['c_id' =>Yii::$app->user->identity->c_id]);
			$query = UserProfile::find();
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);
			$query->joinWith(['user']);
			$query->andFilterWhere(['user.c_id'=>\Yii::$app->user->identity->c_id]);
			
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
			]);
		}
		
		/* $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]); */		
        else return $this->render('report', [
            'programs' => $programs,
            'users' => $users,
        ]);		
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