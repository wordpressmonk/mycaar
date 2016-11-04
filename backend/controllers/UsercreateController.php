<?php

namespace backend\controllers;

use Yii;
use backend\models\Usercreate;
use backend\models\UsercreateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsercreateController implements the CRUD actions for Usercreate model.
 */
class UsercreateController extends Controller
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
     * Lists all Usercreate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsercreateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usercreate model.
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
     * Creates a new Usercreate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usercreate();

        if ($model->load(Yii::$app->request->post()) ) {
			
			$model->auth_key = Yii::$app->security->generateRandomString();				
			$model->load($model);
			
			if($model->save())
			{
				/************** Send Email Process ************/	
				
			Yii::$app
            ->mailer
            ->compose()
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($model->email)
            ->setSubject('Password set for ' . Yii::$app->name)
			 ->setTextBody('Please Set Your Password Link')
			->setHtmlBody('<a href="'.Yii::$app->homeUrl.'/backend/web/site/set-password?authkey='.$model->auth_key.'">Click Here</a>')
            ->send();
		
		
			/************** End Email ************/
			
			Yii::$app->getSession()->setFlash('Success', 'New User Register Successfully!!!.');	
			
            return $this->redirect(['view', 'id' => $model->id]);
				}
			else{
				    return $this->render('create', [
                'model' => $model,
            ]);
			}
			
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Usercreate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('Success', 'Updated the User Successfully!!!.');	
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Usercreate model.
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
     * Finds the Usercreate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usercreate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usercreate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
