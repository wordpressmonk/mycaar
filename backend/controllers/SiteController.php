<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use common\models\SetPassword;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					[
                        'actions' => ['reset-password', 'error'],
                        'allow' => true,
                    ],
					[
                        'actions' => ['pwdregister', 'error'],
                        'allow' => true,
                    ],
					[
                        'actions' => ['forgotten', 'error'],
                        'allow' => true,
                    ],
					[
                        'actions' => ['change-password', 'error'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
		$this->layout = "login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
	
	// Forgot Password Form Generation
	
	public function actionForgotten()
    {
		$this->layout = "login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();		
        if ($model->load(Yii::$app->request->post())) {					
			$userdetails = $model->Usernamecheck($model->username);		
			if($userdetails)
			{
			$model2 = User::find()->where(['id'=>$userdetails->id])->one();
			
			// password_reset_token field is generated
			$model2->generatePasswordResetToken();	
			$model2->scenario = 'apply_forgotpassword';		
			if($model2->save())
			{	
			  // Email Function is "Send Email to respective user"
			   $model2->sendEmailForgotPassword();				
			   Yii::$app->getSession()->setFlash('Success', 'Reset-Password Link Send to Your Email ID!!!.');
			   
			} else {
				Yii::$app->getSession()->setFlash('Error', 'Invalid Username Or Email ID, Please Try Again !!!.');
				 
			}
				return $this->render('forgotten_form', ['model' => $model]);
			} else {
				Yii::$app->getSession()->setFlash('Error', 'Invalid Username Or Email ID, Please Try Again !!!.');
				return $this->render('forgotten_form', ['model' => $model]);
			}
        } else {
            return $this->render('forgotten_form', ['model' => $model]);
        }
		
    }
	
	
	// Email-link through Reset-link
	
	public function actionResetPassword($token)
    {			
		if($token)
		{ 
			$ckuser = User::find()->where(['password_reset_token'=>$token])->one();				
			if($ckuser)
			{	
				$this->layout = "login";
				$model = new SetPassword();
				return $this->render('setpassword', ['model' => $model,'user_id'=>$ckuser->id]);
			} else { 
				return $this->redirect('login');
			}			
		} else { 		
			return $this->redirect('login');
		}
	}
	
	// Generate New Password For Forgot Link 
	
 	public function actionPwdregister()
    {		
		$this->layout = "login";		
		$model = new  SetPassword();			
        if ($model->load(Yii::$app->request->post())) {					
			$userid =  $model->id;									
			$model2 = User::findOne($userid);					
			if($model2)
			{ 
			$model2->setPassword($model->password_hash);				
			$model2->removePasswordResetToken();	
			$model2->scenario = 'apply_setpassword';
			
			if($model2->save())
				Yii::$app->getSession()->setFlash('Success', 'Password Set successfully, Please Login Once!!!.');
			else 
				Yii::$app->getSession()->setFlash('Error', 'Please Try Again!!!.');	
			} else {
			 Yii::$app->getSession()->setFlash('Error', 'Please Try Again!!!.');
			}
			return $this->redirect('login');
		}
		else{			
			return $this->redirect('reset-password');
		}
		
	} 
	
	// Change Password Form Generation
	
	public function actionChangePassword()
    {		
		//$model = new SetPassword();
		$model = Yii::$app->user->identity;
		$model->scenario = 'apply_changepassword';
		 if ($model->load(Yii::$app->request->post())) {			
			 $model->setPassword($model->new_password);			
			 if($model->save())
			 {
				 Yii::$app->getSession()->setFlash('Success', 'You have successfully change your password !!!.');	
			 } else {
				 return $this->render('change_password', ['model' => $model]);				
			 }
			 
			 return $this->refresh();
		 }
		return $this->render('change_password', ['model' => $model]);	
	}
	public function actionTestMail(){
		\Yii::$app
					->mail
					->compose()
					->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' MyCaar'])
					->setTo('dency@abacies.com')
					->setSubject('YOUR VERIFIED EMAIL ID')
					->send();		
	}
}
