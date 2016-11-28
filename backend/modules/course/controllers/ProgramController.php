<?php

namespace backend\modules\course\controllers;

use Yii;
use common\models\Program;
use common\models\search\SearchProgram;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\search\SearchModule;
use yii\filters\AccessControl;
/**
 * ProgramController implements the CRUD actions for Program model.
 */
class ProgramController extends Controller
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
				'only' => ['index','create','view'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
						'roles' => ['superadmin']
                    ],
                    [
                        'actions' => ['create','view','delete'],
                        'allow' => true,
						'roles' => ['company_admin']
                    ],
                ],
            ], 
        ];
    }

    /**
     * Lists all Program models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchProgram();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /**
     * Lists all Program models.
     * @return mixed
     */
    public function actionCompanyPrograms()
    {
		if (\Yii::$app->user->can('superadmin')) {
			return $this->redirect(['index']);
		}
        $searchModel = new SearchProgram();
        $dataProvider = $searchModel->searchCompanyPrograms(Yii::$app->request->queryParams);

        return $this->render('company_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /**
     * Displays a single Program model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		if (\Yii::$app->user->can('manageProgram', ['post' => $model])) {
			$searchModel = new SearchModule();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
			return $this->render('view', [
				'model' => $this->findModel($id),
				'searchModel' => $model,
				'dataProvider' => $dataProvider,
			]);
		}else{
					//yii\web\ForbiddenHttpException
					throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
		}
    }

    /**
     * Creates a new Program model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Program();

        if ($model->load(Yii::$app->request->post()) ) {
			$model->company_id = Yii::$app->user->identity->c_id;
			$model->save();
            return $this->redirect(['view', 'id' => $model->program_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		
        $model = $this->findModel($id);
		if (\Yii::$app->user->can('manageProgram', ['post' => $model])) {
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->program_id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		}else{
			//yii\web\ForbiddenHttpException
			throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
		}
    }

    /**
     * Deletes an existing Program model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		if (\Yii::$app->user->can('manageProgram', ['post' => $model])) {
			$this->findModel($id)->delete();
			return $this->redirect(['index']);
		}else{
			//yii\web\ForbiddenHttpException
			throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
		}
    }

    /**
     * Finds the Program model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Program the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Program::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
