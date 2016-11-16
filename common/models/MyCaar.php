<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "company".
 *
 * @property integer $company_id
 * @property string $name
 * @property string $about_us
 * @property string $logo
 * @property integer $admin
 *
 * @property User $admin0
 */
class MyCaar
{
  	/**
     * Return All Users Details Depends Upon Role Name From DB
     */
    public function getUserAllByrole($rolename)
    {			 	
		$Roleusers = \Yii::$app->authManager->getUserIdsByRole($rolename);		
		$data = User::find()->where(['IN', 'id', $Roleusers])->all();
		return $data;
    }
	
	
	public function getChildRoles($rolename){
		$child_role = [];			
		$child_role_name = \Yii::$app->authManager->getChildRoles($rolename);			
		foreach($child_role_name as $tmp){
			$child_role[$tmp->name] = $tmp->name;
		}
		return $child_role;
	}
	
	public function getRoleNameByUserid($userid)
	{	// Return by dency Copied from common user model		
		$roles = Yii::$app->authManager->getRolesByUser($userid);
		if (!$roles) {
			return null;
		}
		reset($roles);
		/* @var $role \yii\rbac\Role */
		$role = current($roles);

		return $role->name;
	}
}
