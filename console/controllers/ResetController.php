<?php 
namespace console\controllers;

use yii\base\Model;
use yii\console\Controller;

use common\models\Unit;
use yii\helpers\Url;

use Yii;

class ResetController extends Controller {
	
	public function actionUnit($id){
		if(Unit::findOne($id) != null)
			Unit::findOne($id)->resetUnit();
	}
}
?>