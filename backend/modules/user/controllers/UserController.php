<?php

namespace backend\modules\user\controllers;

use Yii;
use common\models\User;
use common\models\MyCaar;
use common\models\UserProfile as Profile;
use common\models\search\SearchUser;
use common\models\ProgramEnrollment;
use common\models\CapabilityAnswer;
use common\models\AwarenessAnswer;
use common\models\Role;
use common\models\Division;
use common\models\Location;
use common\models\State;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['index', 'view','create','update','delete','bulk','multi-delete'],
                        'allow' => true,
						'roles' => ['superadmin']
                    ],
				],
			]
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionBulk(){
		print_R($_POST);
	}
    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
		$profile = new Profile();
		$roles = $this->getRoles();
        if (($model->load(Yii::$app->request->post())) && ($model->validate())) {
			$model->username = $model->email;
			//Random Password Generation For MyCaar Common models
			if(empty($model->password))
			  $model->password = MyCaar::getRandomPassword();
			 
			$model->setPassword($model->password);
			$model->generateAuthKey();
			$model->generatePasswordResetToken();
			
			if($model->save())
			{
				//handle the role first
				$auth = Yii::$app->authManager;
				$authorRole = $auth->getRole($model->role);
				$auth->assign($authorRole, $model->id);
				
		        $profile->user_id = $model->id;
		        $profile->save();
				
				$model->sendEmail($model->password); 
				// Email Function is "Send Email to respective user"
			
				return $this->redirect(['index']);
			}
            else{
				  return $this->render('create', [
                'model' => $model,
				'profile'=> $profile,
				'roles' => $roles,
				]);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
				'profile'=> $profile,
				'roles' => $roles,
            ]);
        }
    }
	public function getRoles(){
		$output = [];
		$query = new Query;
		$query->select('name')
			->from('auth_item')
			->where(['type'=>1]);
		$roles = $query->all();		
		foreach($roles as $role){
			$output[$role['name']] = $role['name'];
		}
		return $output;
	}
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->scenario = 'update_by_admin';
		$model->role = $model->getRoleName();
		$profile = Profile::find()->where(['user_id'=>$id])->one();
		$roles = $this->getRoles();
		
        if (($model->load(Yii::$app->request->post())) && ($model->validate())) {
			//handle the role first
			$model->username = $model->email;
			if($model->save())
			{
				$auth = Yii::$app->authManager;
				$auth->revokeAll($id);
				$authorRole = $auth->getRole($model->role);
				$auth->assign($authorRole, $model->id);
				return $this->redirect(['index']);
			} else {
               return $this->render('update', ['model' => $model,'profile' => $profile,'roles' => $roles,]);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
				'profile' => $profile,
				'roles' => $roles,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
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
			
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Multiple Deletes an existing Division model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	 
	 public function actionMultiDelete()
    {    
		$user_id = Yii::$app->request->post()['user_id'];	
		if($user_id)
		{
			 foreach($user_id as $tmp)
			 {
				if($profile = Profile::findOne(['user_id'=>$tmp]))
				    $profile->delete();
				if($company = Company::findOne(['admin'=>$tmp]))
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
				if($enrollment = ProgramEnrollment::findOne(['user_id'=>$tmp]))
					$enrollment->delete();
				if($capanswer = CapabilityAnswer::findOne(['user_id'=>$tmp]))
					$capanswer->delete();
				if($awarenessanswer = AwarenessAnswer::findOne(['user_id'=>$tmp]))
					$awarenessanswer->delete();
				
			  $this->findModel($tmp)->delete(); 
			 }
		}  			
    }
}
