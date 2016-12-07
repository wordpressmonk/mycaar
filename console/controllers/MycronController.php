<?php

namespace console\controllers;

use yii\base\Model;
use yii\console\Controller;
use common\models\QuickEmail;
use common\models\User;
use yii\helpers\Url;

use Yii;

class MycronController extends Controller {

    public function actionIndex() {	
	echo "runnig..";
		$quickemail = QuickEmail::find()->where(['status'=>0])->all();
		if($quickemail)
		{		
			foreach($quickemail as $tmp)
			{					
			$obj = json_decode($tmp->message,true);	
		  Yii::$app
            ->mail
            ->compose(
                ['html' => 'passwordCron'],
                ['username' => $tmp->to_email,'password'=>$obj['password'],'loginLink'=>$obj['loginLink'],'resetLink'=>$obj['resetLink']] 
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' MyCaar'])
            ->setTo($tmp->to_email)
            ->setSubject($tmp->subject)
            ->send();
					
			  $model = QuickEmail::findOne($tmp->q_id);
			  $model->status = 1;
			  $model->save(); 			  
			}
		}		     		
    }
	public function actionTest(){
		echo "Running..";
	}

}