<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use backend\models\SetPassword;

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
                        'actions' => ['set-password', 'error'],
                        'allow' => true,
                    ],
					[
                        'actions' => ['pwdregister', 'error'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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
	
	public function actionSetPassword($authkey)
    {		
		if(isset($authkey))
		{
			$model = new User();
			$ckuser = User::find()->where(['auth_key'=>$authkey])->one();	
			//$ckuser = User::find()->where(['auth_key'=>$authkey,'password_hash' =>''])->one();	
			
			if(isset($ckuser) && !empty($ckuser))
			{	
				
				$this->layout = "login";
					$model = new SetPassword();
				return $this->render('setpassword', ['model' => $model,'user_id'=>$ckuser->id]);
			} else {
				Yii::$app->getSession()->setFlash('Error', 'Already you used it!!!.');	
				return $this->redirect('login');
			}
			
		} else {		
			Yii::$app->getSession()->setFlash('Error', 'Invalid value !!!.');	
			return $this->redirect('login');
		}
		
        

	}
	
 	public function actionPwdregister()
    {		
		$this->layout = "login";		
		$model = new SetPassword();			
        if ($model->load(Yii::$app->request->post())) {	
			$password = Yii::$app->request->post()['SetPassword']['password_hash'];
			$userid = Yii::$app->request->post()['SetPassword']['userid'];
			
			$password_hash = Yii::$app->security->generatePasswordHash($password);
			$model2 = User::findOne($userid);
			$model2->password_hash = $password_hash;
			$model2->update();			
			Yii::$app->getSession()->setFlash('Success', 'Password Set successfully, Please Login Once!!!.');	
			return $this->redirect('login');
		}
		else{
			Yii::$app->getSession()->setFlash('Error', 'Please Try Again!!!.');
			return $this->redirect('login');
		}
		
		
	
	} 
	
}
