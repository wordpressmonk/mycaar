<?php

namespace backend\modules\course\controllers;

use Yii;
use common\models\Module;
use common\models\search\SearchModule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ModuleController implements the CRUD actions for Module model.
 */
class ModuleController extends Controller
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
     * Lists all Module models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchModule();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Module model.
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
     * Creates a new Module model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Module();

        if ($model->load(Yii::$app->request->post())) {
			//save image here
			//print_r(Yii::$app->request->post());die;
			$model->featured_image = UploadedFile::getInstance($model, 'featured_image');
			if(!empty($model->featured_image)) {
				if(!$model->uploadImage())
					return;
			}
			if($model->save())
            return $this->redirect(['update', 'id' => $model->module_id]);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Module model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$current_image = $model->featured_image;
		$current_video = $model->featured_video_url;
        if ($model->load(Yii::$app->request->post())) {
			
			//Save featured image here
			$model->featured_image = UploadedFile::getInstance($model, 'featured_image');
			if(!empty($model->featured_image)) {
				if(!$model->uploadImage())
					return;
			}
			else
				$model->featured_image = $current_image;
			//end of saving image
			
			//save featured video
			$model->featured_video_url = UploadedFile::getInstance($model, 'featured_video_url');
			if(!empty($model->featured_video_url)) {
				if(!$model->uploadVideo())
					return;
			}
			else
				$model->featured_video_url = $current_video;
			//end of saving video
			
			if($model->save())
				return $this->redirect(['update', 'id' => $model->module_id]);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Module model.
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
     * Finds the Module model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Module the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Module::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
