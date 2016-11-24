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
use common\models\search\SearchEnrolment;
use common\models\Role;
use common\models\Division;
use common\models\Location;
use common\models\State;
use common\models\Program;
use common\models\QuickEmail;
use common\models\ProgramEnrollment;
use common\models\Enrolment as Enrollment;
use yii\db\Query;
use yii\db\Command;
use yii\db\Connection;
use yii\helpers\ArrayHelper;


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
			$quickemail = new QuickEmail();					
			$roles = MyCaar::getChildRoles('company_admin');	
			
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
				// Email Function is "Send Email to respective user"
				$subject = "Verification Mail";
				$fromemail = "info_notification@gmail.com";
				$toemail = $model->email;
				$username = $profile->firstname." ".$profile->lastname;
				$message = "<br><br> Your Password:".$model->password."<br><br>After login, Please Kindly Delete this Message for security.";
				 $email_status = Yii::$app->mail->compose(['html' => 'passwordSend-text'],['username'=>$username,'message'=>$message])
				->setFrom($fromemail)
				->setTo($toemail)
				->setSubject($subject)
				->send();
				
				if($profile->load(Yii::$app->request->post()))
				{
				 $profile->user_id = $model->id;			
				 $profile->save();	
				}
				
				// Email Message is saved in database
				
				$quickemail->c_id = Yii::$app->user->identity->c_id;
				$quickemail->to_email = $toemail;
				$quickemail->from_email = $fromemail;
				$quickemail->subject = $subject;
				$quickemail->message = $message;
				$quickemail->status = $email_status;			
				$quickemail->save();	
				
				
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
			$model->scenario = 'update_by_company_admin';
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
				 
		///GET VAlues and Stored in Array with Key //////	 
		
			$getlocationname = ArrayHelper::map(Location::find()->select(['location_id','name'])->where(['company_id'=>Yii::$app->user->identity->c_id])->asArray()->all(), 'location_id', 'name');
			 
			$getdivisionname = ArrayHelper::map(Division::find()->select(['division_id','title'])->where(['company_id'=>Yii::$app->user->identity->c_id])->asArray()->all(), 'division_id', 'title');
			 
			$getrolename = ArrayHelper::map(Role::find()->select(['role_id','title'])->where(['company_id'=>Yii::$app->user->identity->c_id])->asArray()->all(), 'role_id', 'title');
			 
			$getstatename = ArrayHelper::map(State::find()->select(['state_id','name'])->where(['company_id'=>Yii::$app->user->identity->c_id])->asArray()->all(), 'state_id', 'name');
			
				/// Upload Excel File Read The File Function  
				
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
				  
				 //$row is start 2 because first row assigned for heading. 
				 
				 for($row=2; $row<=$highestRow; ++$row)
				 {                  				 
					 $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);				   
					//save to User  table.
					 $usertable = new User();
					if(\Yii::$app->user->can('company_admin')) {
						$usertable->scenario = 'update_by_admin';
						}						
						 $profiletable = new Profile();	 
						 $usertable->email = $rowData[0][0];					
						 $usertable->username = $usertable->email;					 
						 $usertable->generateAuthKey();
						 $usertable->c_id = Yii::$app->user->identity->c_id; 					
						 $usertable->role = 'user';	
						 
		/**  Random Password Generator **/
		
						 $random_pwd = sprintf("%06d", mt_rand(1, 999999));						 
						 $usertable->password_hash = Yii::$app->security->generatePasswordHash($random_pwd);
						 
		//***** Role check and Create Function *** //	
		

			$getroleid = array_search(strtolower(trim($rowData[0][1])), array_map('strtolower', $getrolename));
			  if($getroleid)
				{
					$profiletable->role = $getroleid;
				} else {
					$rolenew = new Role();	
					$rolenew->title = trim($rowData[0][1]);
					$rolenew->company_id = $usertable->c_id;
					$rolenew->save();
					
				$newrole = array( $rolenew->role_id =>trim($rowData[0][1]));
				$profiletable->role = $rolenew->role_id;
				$getrolename = $getrolename + $newrole;				
				}
				
				
		//***** Division check and Create Function *** ///		
								
			  $getdivisionid = array_search(strtolower(trim($rowData[0][3])), array_map('strtolower', $getdivisionname));
			  if($getdivisionid)
				{
					$profiletable->division = $getdivisionid;
				} else {
					$divisionnew = new Division();	
					$divisionnew->title = trim($rowData[0][3]);
					$divisionnew->company_id = $usertable->c_id;
					$divisionnew->save();
					
				$newdivision = array( $divisionnew->division_id =>trim($rowData[0][3]));
				$profiletable->division = $divisionnew->division_id;
				$getdivisionname = $getdivisionname + $newdivision;				
				}
				
	   //***** Location check and Create Function *** ///						 						 
			
			  $getlocationid = array_search(strtolower(trim($rowData[0][4])), array_map('strtolower', $getlocationname));
			  if($getlocationid)
				{
					$profiletable->location = $getlocationid;
				} else {
					$locationnew = new Location();	
					$locationnew->name = trim($rowData[0][4]);
					$locationnew->company_id = $usertable->c_id;
					$locationnew->save();
					
				$newlocation = array( $locationnew->location_id =>trim($rowData[0][4]));
				$profiletable->location = $locationnew->location_id;
				$getlocationname = $getlocationname + $newlocation;				
				}
				
				
	   //***** State check and Create Function *** ///			
	   
				 $getstateid = array_search(strtolower(trim($rowData[0][7])), array_map('strtolower', $getstatename));
			  if($getstateid)
				{
					$profiletable->state = $getstateid;
				} else {
					$statenew = new State();	
					$statenew->name = trim($rowData[0][7]);
					$statenew->company_id = $usertable->c_id;
					$statenew->save();
					
				$newstate = array( $statenew->state_id =>trim($rowData[0][7]));
				$profiletable->state = $statenew->state_id;
				$getstatename = $getstatename + $newstate;				
				}		
						
			//////////////////////////////
			
						 $profiletable->employee_number = $rowData[0][2];
						 $profiletable->firstname = $rowData[0][5];
						 $profiletable->lastname = $rowData[0][6];			
					 if($usertable->save())
					 {			 
						$auth = Yii::$app->authManager;
						$authorRole = $auth->getRole($usertable->role);
						$auth->assign($authorRole, $usertable->id); 
						$profiletable->user_id =  $usertable->id;							
					 	$profiletable->save();  
												
			/******** Send Mail Function to Each User **********/
				
				$subject = "Verification Mail";
				$fromemail = "info_notification@gmail.com";
				$toemail = $rowData[0][0];
				$username= $profiletable->firstname." ".$profiletable->lastname;
				$message = "<br><br> Your Password:".$random_pwd."<br><br>After login, Please Kindly Delete this Message for security.";
				 $email_status = Yii::$app->mail->compose(['html' => 'passwordSend-text'],['username'=>$username,'message'=>$message])
				->setFrom($fromemail)
				->setTo($toemail)
				->setSubject($subject)
				->send();
				
				// Email Message is saved in database
				$quickemail = new QuickEmail();	
				$quickemail->c_id = Yii::$app->user->identity->c_id;
				$quickemail->to_email = $toemail;
				$quickemail->from_email = $fromemail;
				$quickemail->subject = $subject;
				$quickemail->message = $message;
				$quickemail->status = $email_status;			
				$quickemail->save();	
				
		   /******** Send Mail Function to Each User **********/		
		  
					 } else 
					 {						
						 $errors = $usertable->errors;
						 reset($errors);
						 $listerror = current($errors);
						 $error_report[] = $usertable->username." -- ".$listerror[0];
					 }					 
				 }				
				if(isset($error_report) && empty($error_report))
				{
					 Yii::$app->getSession()->setFlash('Success', 'Upload the User Details Sucessfully!!!.');
				}
				else{
					 Yii::$app->getSession()->setFlash('Error', 'User Details Failed To Imported Following Reasons !!!.');
					 Yii::$app->getSession()->setFlash('Error-data', $error_report); 
				}				
				return $this->render('upload_form', ['model' => $model]);						
		}else {
				return $this->render('upload_form', ['model' => $model ]);
			  }
    }	
	
	public function actionEnrollUser($program_id=false)
    {		
		$model = new Enrollment();	
        $searchModel = new SearchEnrolment();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		if($post=Yii::$app->request->post()){	
			if(isset($post['Program']) && ($post['action'] === "enrolled") && isset($post['selection']) )
			{				
					foreach($post['selection'] as $tmp1)
					{
						$model1 = new ProgramEnrollment();	
						$check_userid = $model1::find()->where(['program_id'=>$post['Program'],'user_id'=>$tmp1])->one();
						if(!$check_userid)
						{
							$model1->program_id = $post['Program'];
							$model1->user_id = $tmp1;
							$model1->save();
						} 
					}				
			}
			else if(isset($post['Program']) && ($post['action'] === "unenrolled") && isset($post['selection']))
			{				
					foreach($post['selection'] as $tmp2)
					{
						$model2 = new ProgramEnrollment();	
						$model2 = $model2::find()->where(['program_id'=>$post['Program'],'user_id'=>$tmp2])->one();
						if($model2)
							$model2->delete();
					}								
			}						
			 return $this->render('enroll_user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,'model' => $model,'program_id'=>$program_id
				]);
		}
        else { 
				
		
			return $this->render('enroll_user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,'model' => $model,'program_id'=>$program_id
			]);
			}
    }
}
