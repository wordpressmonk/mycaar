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
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use common\models\search\SearchUser;
use common\models\search\SearchEnrolment;
use common\models\search\SearchProgramEnrollment;
use common\models\ProgramEnrollment;
use common\models\CapabilityAnswer;
use common\models\AwarenessAnswer;
use common\models\Role;
use common\models\Division;
use common\models\Location;
use common\models\State;
use common\models\Enrolment as Enrollment;



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
			'access' => [
				'class' => AccessControl::className(),
                'rules' => [
                   
					 [
                        'actions' => ['index','create','view','update','delete','index-user','create-user','view-user','update-user','delete-user','enroll-user','multi-delete','multi-delete-user','ajax-new-user','removelogo'],
                        'allow' => true,
						'roles' => ['company_admin']
                    ],
					 [
                        'actions' => ['index-role-user','view-role-user','enroll-user','my-profile','update-my-profile'],
                        'allow' => true,
						'roles' => ['assessor']
                    ],
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
			{				
				$user = User::findOne($model->admin);
				$user->c_id = $model->company_id;								
				$user->save(false);					
				return $this->redirect(['index']);			
			} else
				return $this->render('create', ['model' => $model]);
			
        } else {	 	
         return $this->render('create', ['model' => $model]);
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
		 if(\Yii::$app->user->can('superadmin')) { 
		}
		else if(\Yii::$app->user->can('company_admin')) {
			$model->scenario = 'update_by_company_admin';
		}
			
        if (($model->load(Yii::$app->request->post()))) {	 
			/**		Company Logo Image Uploaded for Update function Line 
			**/
			$model->logo = UploadedFile::getInstance($model, 'logo');			
				if(!empty($model->logo)){ 
					if(!$model->uploadImage())
						return;
				}else
					$model->logo = $current_image;
			
			if($model->save())
			{			
			/**		Company admin User can Change by Superadmin and System admin Line 
			**/			 			
					$user = User::findOne($model->admin);
					$user->c_id = $model->company_id;								
					$user->save(false); 								
				
				 if(\Yii::$app->user->can('superadmin')) 
					return $this->redirect(['index']);	
				else if(\Yii::$app->user->can('company_admin')) 
					return $this->redirect(['view','id'=>$model->company_id]);	
				
			}else 
				return $this->render('update', ['model' => $model]);
		
        } else {
            return $this->render('update', ['model' => $model]);
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
			$profile->scenario = 'company_admin_user';
		if(($model->load(Yii::$app->request->post())) && ($profile->load(Yii::$app->request->post())) && ($model->validate()) && ($profile->validate()))   {	
			$model->username = $model->email;
		if(empty($model->password))
			 $model->password = MyCaar::getRandomPassword();
			 
			$model->generatePasswordResetToken();
			$model->setPassword($model->password);
			$model->generateAuthKey();
			$model->c_id = Yii::$app->user->identity->c_id;						
			if($model->save())
			{				
				//handle the role first				
				$auth = Yii::$app->authManager;
				$authorRole = $auth->getRole($model->role);
				$auth->assign($authorRole, $model->id); 
				//save profile first				
				 $profile->user_id = $model->id;			
				 $profile->save();	
				
					
				// Email Function is "Send Email to respective user"
				$model->sendEmail($model->password); 
				return $this->redirect(['index-user']);
			} else
			{					
				return $this->render('create_user', ['model' =>$model,'profile'=>$profile,'roles'=>$roles]);
			}			
        } else {			
            return $this->render('create_user', ['model' => $model,'profile'=>$profile,'roles'=>$roles]);	
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
		if($profile = Profile::findOne(['user_id'=>$id]))
				    $profile->delete();
				if($company = Company::findOne(['admin'=>$id]))
				{
				
				if($division = Division::findOne(['company_id'=>$company->company_id]))
					$division->delete();
				if($location = Location::findOne(['company_id'=>$company->company_id]))
					$location->delete();
				if($state = State::findOne(['company_id'=>$company->company_id]))
					$state->delete();
				if($role = Role::findOne(['company_id'=>$company->company_id]))
					$role->delete();
				
				$company->delete();
				}
				if($enrollment = ProgramEnrollment::findOne(['user_id'=>$id]))
					$enrollment->delete();
				if($capanswer = CapabilityAnswer::findOne(['user_id'=>$id]))
					$capanswer->delete();
				if($awarenessanswer = AwarenessAnswer::findOne(['user_id'=>$id]))
					$awarenessanswer->delete();
		
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
		
        $profile->scenario = 'company_admin_user';
		if(($model->load(Yii::$app->request->post())) && ($profile->load(Yii::$app->request->post())) && ($model->validate()) && ($profile->validate()))   {
			//handle the role first
			$model->username = $model->email;			
			if($model->save())
			{
			//handle the role first		
			$auth = Yii::$app->authManager;
			$auth->revokeAll($id);
			$authorRole = $auth->getRole($model->role);
			$auth->assign($authorRole, $model->id);
			//save profile first			
			$profile->user_id = $model->id;			
			$profile->save();	
								
             return $this->redirect(['index-user']);
			
			} else {
            return $this->render('update_user', ['model' => $model,'profile' => $profile,'roles' => $roles,]);
			}
        } else {
            return $this->render('update_user', [
                'model' => $model,
				'profile' => $profile,
				'roles' => $roles,
            ]);
        }
    }

	public function actionEnrollUser($program_id=false)
    {		
		$model = new User();	
        $searchModel = new SearchProgramEnrollment();
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
			return $this->redirect(['enroll-user','program_id'=>$post['Program']]);			
		}
        else { 
			return $this->render('enroll_user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,'model' => $model,'program_id'=>$program_id
			]);
			}
    }
	
	
	 /** Company Logo Remove Function Through AJAX Call 
     **/	 	 
	public function actionAjaxNewUser()
    {       
		$model = new User();	
		$profile = new Profile();	
		$model->email = Yii::$app->request->post()['emailid'];
		$model->username = $model->email;
		$model->role = 'company_admin';
		$model->password = MyCaar::getRandomPassword();
		$model->setPassword($model->password);
	    $model->generateAuthKey();
	
		if($model->save()){				
				$auth = Yii::$app->authManager;
				$authorRole = $auth->getRole($model->role);
				$auth->assign($authorRole, $model->id);				
		        $profile->user_id = $model->id;
		        $profile->save();				
				$model->sendEmail($model->password); 
			
			echo "<option value=".$model->id.">".$model->email."</option>";
				
		} else {
			echo "false";
		}
			
    }
	
	
	/**
     * Multiple Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	 
	 public function actionMultiDelete()
    {    
		$role_id = Yii::$app->request->post()['role_id'];
		if($role_id)
		{
			 foreach($role_id as $tmp)
			  $this->findModel($tmp)->delete(); 
		} 
			
    }
	
	/**
     * Multiple Deletes an existing User for company admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	 
	 public function actionMultiDeleteUser()
	 {    
			$user_id = Yii::$app->request->post()['user_id'];	
			if($user_id)
			{
				$current_user = User::findOne(\Yii::$app->user->id);
				$current_role = $current_user->getRoleName();
				 foreach($user_id as $tmp)
				 {
					
					$user = User::findOne($tmp);
					if(\Yii::$app->user->can($user->getRoleName()) && $current_role != $user->getRoleName() && $current_user->id != $user->id)
						$user->deleteUser(); 
				 }
			}  			
		}
	
	// Index User Page of All User  for the Assessor Role User
	
	public function actionIndexRoleUser()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_role_user', [
            'searchModel' => $searchModel,
             'dataProvider' => $dataProvider, 
        ]);
    }
	
	// View User Page of All User  for the Assessor Role User
	
	public function actionViewRoleUser($id)
    {		
	  if (($model = User::findOne($id)) !== null) {           
		  $profile = Profile::findOne(['user_id'=>$id]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
		
          return $this->render('view_role_user', [
            'model' =>$profile,
        ]); 
    }
	
	// My Profile Page for the Assessor Role User
	
	public function actionMyProfile()
    {		
		$id = Yii::$app->user->identity->id;
	   if (($model = User::findOne($id)) !== null) {           
		  $profile = Profile::findOne(['user_id'=>$id]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 		
          return $this->render('view_my_profile', [
            'model' =>$profile,
        ]); 
    }
	
		// Update My Profile Page for the Assessor Role User
	
	public function actionUpdateMyProfile()
    {       
		$id = Yii::$app->user->identity->id;
		 if (($model = User::findOne($id)) !== null) {           
		  $profile = Profile::findOne(['user_id'=>$id]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
		
		$model->role = $model->getRoleName();
			
		if(\Yii::$app->user->can('company_admin')) {
			$model->scenario = 'update_by_company_admin';
		}
		
        $profile->scenario = 'company_admin_user';
		if(($model->load(Yii::$app->request->post())) && ($profile->load(Yii::$app->request->post())) && ($model->validate()) && ($profile->validate()))   {
			//handle the role first
			$model->username = $model->email;			
			if($model->save())
			{					
			$profile->user_id = $model->id;			
			$profile->save();									
             return $this->redirect(['my-profile']);
			
			} else {
            return $this->render('update_my_profile', ['model' => $model,'profile' => $profile,]);
			}
        } else {
            return $this->render('update_my_profile', ['model' => $model,'profile' => $profile,]);
        }
    }
	
}
