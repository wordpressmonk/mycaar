<?php

namespace backend\modules\course\controllers;

use Yii;
use common\models\Module;
use common\models\Unit;
use common\models\UnitElement;
use common\models\search\SearchUnit;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class UnitController extends Controller
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
     * Lists all Unit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUnit();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Unit model.
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
     * Creates a new Unit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($m_id)
    {
        $model = new Unit();
		$module = Module::find()->where(['module_id'=>$m_id])->one();
		if(!$module){
			return false;
		}
        if(isset(Yii::$app->request->post()['unit_title'])) {
			//print_r(Yii::$app->request->post());
			//$data = json_decode(Yii::$app->request->post()['builder_data']);
			//json_decode(stripslashes($_POST['posts']));
			//print_r($data->html);
			$model->module_id = $m_id;
			$model->title = Yii::$app->request->post()['unit_title'];
			$model->status = Yii::$app->request->post()['unit_status'];
			if($model->save()){
				//save elements as well
				$element = new UnitElement();
				$element->unit_id = $model->unit_id;
				$element->element_type = "page";
				$element->element_order = 1;
				$element->content = Yii::$app->request->post()['builder_data'];
				$element->save();
			}			
            return $this->redirect(['update', 'id' => $model->unit_id]);
        } else {
            return $this->render('add_unit', [
                'model' => $model,
				'module' => $module,
            ]);
        }
    }

    /**
     * Updates an existing Unit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$module = Module::find()->where(['module_id'=>$model->module_id])->one();
		
        if(isset(Yii::$app->request->post()['unit_title'])) {
			//print_r(Yii::$app->request->post());
			//$data = json_decode(Yii::$app->request->post()['builder_data']);
			//json_decode(stripslashes($_POST['posts']));
			//print_r($data->html);
			//$model->module_id = $m_id;
			$model->title = Yii::$app->request->post()['unit_title'];
			$model->status = Yii::$app->request->post()['unit_status'];
			if($model->save()){
				//save elements as well
				$element = UnitElement::find()->where(['unit_id'=>$id])->one();
				$element->unit_id = $model->unit_id;
				$element->element_type = "page";
				$element->element_order = 1;
				$element->content = Yii::$app->request->post()['builder_data'];
				$element->save();
			}
            return $this->redirect(['update', 'id' => $model->unit_id]);
        } else {
            return $this->render('update_unit', [
                'model' => $model,
				'module' => $module,
            ]);
        }
    }

    /**
     * Deletes an existing Unit model.
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
     * Finds the Unit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Unit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionUpload(){
		$dir = "uploads/";
		move_uploaded_file($_FILES["media"]["tmp_name"], $dir. $_FILES["media"]["name"]);
		return \Yii::$app->homeUrl.$dir. $_FILES["media"]["name"];
	}
	
	public function actionSaveAwarenessTest(){
		//print_r(Yii::$app->request->post());
		$data = json_decode(Yii::$app->request->post()['awareness_data']);
		//$data = Yii::$app->request->post()['awareness_data'];
		//print_r($data->html);die;
		$dom = new \DomDocument();
/* 		$html = 
		'<form>
			<p>
				<select type="radio-group" label="Single Choice" class="radio-group" name="radio-group-1479116923275" src="false">
					<option label="option-1" value="option-1" selected="true">option-1</option>
					<option label="option-2" value="option-2">option-2</option>
				</select>
				<select type="fileupload" label="File Upload" class="file-input" name="fileupload-1479116934816" src="false"></select>
				<select type="text" label="Answer Field" subtype="text" class="form-control" name="text-1479116932882" src="false"></select>
				<select type="checkbox-group" label="Multiple Choice" class="checkbox-group" name="checkbox-group-1479116930314" src="false">
					<option label="option-1" value="option-1" selected="true">option-1</option>
					<option label="option-2" value="option-2">option-2</option>
					<option label="option-3" value="option-3">option-3</option>
				</select>
			</p>
		</form>'; */
		$matches = [];
		//preg_match_all('/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/', $html,$matches, PREG_SET_ORDER);
		//preg_match_all('~<field (.*?)>(.*?)</field>~', $html,$matches, PREG_SET_ORDER);
		//print_r($matches);
		$dom->loadHTML($data->html);
foreach($dom->getElementsByTagName('input') as $element) {
	print_r($element);
}
   
		//print_r($dom);
	}
}
