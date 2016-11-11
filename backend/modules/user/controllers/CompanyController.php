<?php

namespace backend\modules\user\controllers;

use Yii;
use common\models\Company;
use common\models\search\SearchCompany;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use yii\web\UploadedFile;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCompany();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        if ($model->load(Yii::$app->request->post())) {	
		/**		Company Logo Image Uploaded for Created function Line --- Connected with "Company Module"
			**/
			
			$model->logo = UploadedFile::getInstance($model, 'logo');			
			if(!empty($model->logo)) { 
				if(!$model->uploadImage())
					return;
			}			
			if($model->save())
				return $this->redirect(['view', 'id' => $model->company_id]);
        } else {		
         return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$current_image = $model->logo;
		/**		Company admin User Edit Validation Rule is changed  Line --- Connected with "Company Module"
		**/
		if(\Yii::$app->user->can('company_admin')) {
			$model->scenario = 'update_by_company_admin';
		}
			
        if ($model->load(Yii::$app->request->post())) {	
			/**		Company Logo Image Uploaded for Update function Line 
			**/
			$model->logo = UploadedFile::getInstance($model, 'logo');			
				if(!empty($model->logo)){ 
					if(!$model->uploadImage())
						return;
				}else
					$model->logo = $current_image;
		
			/**		Company admin User can Change by Superadmin and System admin Line 
			**/
		 if(\Yii::$app->user->can('company manage')){ 
				$cmpyadmin_id = Yii::$app->request->post('Company')['admin'];
				$user = User::findOne($cmpyadmin_id);
				$user->c_id = $model->company_id;								
				$user->save(false); 								
			} 
			
			if($model->save())
				return $this->redirect(['view', 'id' => $model->company_id]);
		
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Company model.
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	 /** Company Logo Remove Function Through AJAX Call 
     **/	 	 
	public function actionRemovelogo()
    {       
		$company_id = Yii::$app->request->post('company_id');
		$company_details = $this->findModel($company_id);
		$company_details->logo = "";
		$company_details->save();      
    }
	
}
