<?php

namespace backend\modules\user\controllers;

use Yii;
use common\models\User;
use common\models\UserProfile as Profile;
use common\models\search\SearchUser;
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
                        'actions' => ['index', 'view','create','update','delete'],
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
        if ($model->load(Yii::$app->request->post())) {
			$model->setPassword($model->password);
			$model->generateAuthKey();
			if($model->save())
			{
				//handle the role first
				$auth = Yii::$app->authManager;
				$authorRole = $auth->getRole($model->role);
				$auth->assign($authorRole, $model->id);
				//$model->sendEmail(); develop this function
				$profile->user_id = $model->id;
				$profile->save();
				return $this->redirect(['view', 'id' => $model->id]);
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//handle the role first
			$auth = Yii::$app->authManager;
			$auth->revokeAll($id);
			$authorRole = $auth->getRole($model->role);
			$auth->assign($authorRole, $model->id);
            return $this->redirect(['view', 'id' => $model->id]);
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
}
