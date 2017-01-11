<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use common\models\SiteMeta;
use common\models\SetPassword;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class DatamigrationController extends Controller
{
	public function fetch($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec ($ch);
		curl_close ($ch);
		return $output = json_decode($data);
	}
	
	public function actionFetchLocation()
    {
		
		$url = 'http://mycaar.com.au/wp-content/data_extract.php?type=location';
		$locations = $this->fetch($url);
		foreach( $locations  as $location  )
		{
			$new = new \common\models\Location();
			$new->name = $location;
			$new->company_id = 10;
			$new->save();
		}
	
    }
	
	
	
	public function actionFetchDivision()
    {
		
		$url = 'http://mycaar.com.au/wp-content/data_extract.php?type=division';
		$divisions = $this->fetch($url);
		foreach( $divisions  as $division  )
		{
			$new = new \common\models\Division();
			$new-> title = $division;
			$new->company_id = 10;
			$new->save();
		}
	
    }
	
	
	public function actionFetchState()
    {
		
		$url = 'http://mycaar.com.au/wp-content/data_extract.php?type=state';
		$states = $this->fetch($url);
		foreach( $states  as $state  )
		{
			$new = new \common\models\State();
			$new->name = $state;
			$new->company_id = 10;
			$new->save();
		}
	
    }

	public function actionFetchRole()
    {
		
		$url = 'http://mycaar.com.au/wp-content/data_extract.php?type=role';
		$roles = $this->fetch($url);
		foreach( $roles  as $role  )
		{
			$new = new \common\models\Role();
			$new->title = $role;
			$new->company_id = 10;
			$new->save();
		}
	
    }
	
	public function actionFetchUser()
    {

		$url = 'http://mycaar.com.au/wp-content/data_extract.php?type=user';
		$users = $this->fetch($url);
		$i = 0;
		$password = "reset123";
		foreach( $users  as $user  )
		{	
			if($i == 0)
			{
				$i = $i + 1;
				continue;			
			}
			else if( $user->email == 'andrew@mgatraining.com.au')
				continue;	
			
			$i = $i + 1;
			$new_user = new \common\models\User();
			$new_user->username = $user->email; 
			$new_user->email = $user->email; 			
			$new_user->setPassword($password);
			$new_user->generateAuthKey();
			$new_user->generatePasswordResetToken();
			$new_user->c_id = 10;
		
			if($new_user->save(false))
			{ 			
			 if($user->accesslevel == "subscriber")
				$rolename = "user";
			 else if($user->accesslevel == "assessor")
			 	$rolename = "assessor";
			 else if($user->accesslevel ==  "administrator")	
				$rolename = "company_admin";
			
			 $auth = Yii::$app->authManager;
			 $authorRole = $auth->getRole($rolename);
			 $auth->assign($authorRole, $new_user->id); 
				
			if(!empty($user->division) && ($user->division !=0) )	
				$division = \common\models\Division::find()->select('division_id')->where(['title'=>$user->division,'company_id'=>10])->one()->division_id;
			else 
				$division = "";	
			
			if(!empty($user->role) && ($user->role !=0))	
				$role = \common\models\Role::find()->select('role_id')->where(['title'=>$user->role,'company_id'=>10])->one()->role_id;
			else 
				$role = "";	
			
			if(!empty($user->state) && ($user->state !=0))	
				$state = \common\models\State::find()->select('state_id')->where(['name'=>$user->state,'company_id'=>10])->one()->state_id;
			else
				$state = "";	
			
			if(!empty($user->location) && ($user->location !=0))	
				$location = \common\models\Location::find()->select('location_id')->where(['name'=>$user->location,'company_id'=>10])->one()->location_id;
			else
				$location = "";
			
			$new_userprofile = new \common\models\UserProfile();
			$new_userprofile->user_id = $new_user->id;
			$new_userprofile->firstname = $user->firstname;
			$new_userprofile->lastname = $user->lastname; 
			$new_userprofile->division = $division; 
			$new_userprofile->role = $role; 
			$new_userprofile->state = $state; 
			$new_userprofile->location = $location; 
			$new_userprofile->save();
			
		/******** Send Mail Function to DataBase **********/
				
				// Email Message is saved in database
				$quickemail = new \common\models\QuickEmail();	
				$quickemail->c_id = 10;
				$quickemail->user_id = $new_user->id;
				$quickemail->to_email = $user->email;
				$quickemail->from_email = "info_notification@gmail.com";
				$quickemail->subject = "Please Verified your Email";
				$loginLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/login']);
				$resetLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/reset-password', 'token' => $new_user->password_reset_token]);
				$arr = array('password' => $password, 'loginLink' => $loginLink, 'resetLink' => $resetLink);
				$quickemail->message = json_encode($arr);
				$quickemail->status = 0;			
				$quickemail->save();	
				
		   /******** Send Mail Function to Each User **********/	
		   
			
			
			}
				
		} 
		echo "<pre>";
		print_r($i);
		
    }
	
	
}
