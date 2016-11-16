<?php

namespace backend\modules\user\controllers;

use Yii;
use common\models\Company;
use common\models\MyCaar;
use common\models\search\SearchCompany;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\UserProfile as Profile;
use common\models\ImportFile;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use common\models\search\SearchUser;

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
	public function actionCreateUser(){					
			$model = new User();
			$profile = new Profile();					
			$roles = MyCaar::getChildRoles('company_admin');	

		if(\Yii::$app->user->can('company_admin')) {
			$model->scenario = 'update_by_admin';
		}
		
		if($model->load(Yii::$app->request->post()))  {
			$model->setPassword($model->password);
			$model->generateAuthKey();
			$model->c_id = Yii::$app->user->identity->c_id;

			if($model->save())
			{				
				//handle the role first				
				$auth = Yii::$app->authManager;
				$authorRole = $auth->getRole($model->role);
				$auth->assign($authorRole, $model->id); 
				
				//$model->sendEmail(); develop this function
				
				if($profile->load(Yii::$app->request->post()))
				{
				 $profile->user_id = $model->id;			
				 $profile->save();	
				}
				
				return $this->redirect(['view-user', 'id' => $model->id]);
			} else
			{	
				return $this->render('create_user', ['model' => $model,'profile'=>$profile,'roles'=>$roles,]);
			}			
        } else {
			
            return $this->render('create_user', [
                'model' => $model,'profile'=>$profile,'roles'=>$roles,
            ]);	
		}		
	}	
	
	
	public function actionIndexUser()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_user', [
            'searchModel' => $searchModel,
             'dataProvider' => $dataProvider, 
        ]);
    }
	
	
	 public function actionViewUser($id)
    {		
	  if (($model = User::findOne($id)) !== null) {           
		  $profile = Profile::findOne(['user_id'=>$id]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
		
          return $this->render('view_user', [
            'model' =>$profile,
        ]); 
    }
	
	
	 public function actionDeleteUser($id)
    {		
		Profile::findOne(['user_id'=>$id])->delete();
        User::findOne($id)->delete();
        return $this->redirect(['index-user']);
    }
	
	
	public function actionUpdateUser($id)
    {       
		    $model = User::findOne($id);
			$profile = Profile::find()->where(['user_id'=>$id])->one();				
			$roles = MyCaar::getChildRoles('company_admin');	
			$model->role = $model->getRoleName();
		if(\Yii::$app->user->can('company_admin')) {
			$model->scenario = 'update_by_admin';
		}
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//handle the role first
			$auth = Yii::$app->authManager;
			$auth->revokeAll($id);
			$authorRole = $auth->getRole($model->role);
			$auth->assign($authorRole, $model->id);
			
			if($profile->load(Yii::$app->request->post()))
				{
				 $profile->user_id = $model->id;			
				 $profile->save();	
				}
				
            return $this->redirect(['view-user', 'id' => $model->id]); 
        } else {
            return $this->render('update_user', [
                'model' => $model,
				'profile' => $profile,
				'roles' => $roles,
            ]);
        }
    }

   public function actionImportexcel()
    {
			   $model = new ImportFile();			  
			if($model->load(Yii::$app->request->post())){	
				
				$model->upfile = UploadedFile::getInstance($model, 'upfile');				
				$inputFiles = $model->upfile->tempName ;					
				  try{
					 $inputFileType = \PHPExcel_IOFactory::identify($inputFiles);
					 $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					 $objPHPExcel = $objReader->load($inputFiles);
					 
				  } catch (Exception $ex) {             
					 die('Error in File Formate');
				  }
						  
				  $sheet = $objPHPExcel->getSheet(0);
				  $highestRow = $sheet->getHighestRow();
				  $highestColumn = $sheet->getHighestColumn();				
				  $error_report = [];
				  $password = [];
				
				 //$row is start 2 because first row assigned for heading.         
				 for($row=2; $row<=$highestRow; ++$row)
				 {                  				 
					 $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);				   
					//save to User  table.
					 $usertabel = new User();
					if(\Yii::$app->user->can('company_admin')) {
						$usertabel->scenario = 'update_by_admin';
						}
						
						 $profiletabel = new Profile();	 
						 $usertabel->email = $rowData[0][0];					
						 $usertabel->username = $usertabel->email;					 
						 $usertabel->generateAuthKey();
						 $usertabel->c_id = Yii::$app->user->identity->c_id; 					
						 $usertabel->role = 'user';
						// $password[] = \Yii::app()->getSecurityManager()->generateRandomString(6);	
						
						 $usertabel->password_hash= $password_hash = Yii::$app->security->generatePasswordHash('123456');
						 $profiletabel->role = $rowData[0][1];
						 $profiletabel->employee_number = $rowData[0][2];
						 $profiletabel->division = $rowData[0][3];
						 $profiletabel->location = $rowData[0][4];
						 $profiletabel->firstname = $rowData[0][5];
						 $profiletabel->lastname = $rowData[0][6];
						 $profiletabel->state = $rowData[0][7]; 
			
					 if($usertabel->save())
					 {			 
						$auth = Yii::$app->authManager;
						$authorRole = $auth->getRole($usertabel->role);
						$auth->assign($authorRole, $usertabel->id); 
						$profiletabel->user_id =  $usertabel->id;	
					 	$profiletabel->save();  
					 } else 
					 {
						$error_report[] = $usertabel->email;
					 }					 
				 }		 			   					
				if(isset($error_report) && empty($error_report))
				{
					 Yii::$app->getSession()->setFlash('Success', 'Upload the User Details Sucessfully!!!.');
				}
				else{
					 Yii::$app->getSession()->setFlash('Error', 'User Details Failed Following User Email!!!.');
					 Yii::$app->getSession()->setFlash('Error-data', $error_report); 
				}
				
				echo "<pre>";
				print_r($password);
				exit;
				
				return $this->render('upload_form', ['model' => $model]);						
		}else {
				return $this->render('upload_form', ['model' => $model ]);
			  }
    }		
}
