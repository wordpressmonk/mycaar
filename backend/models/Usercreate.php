<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $c_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AwarenessAnswer[] $awarenessAnswers
 * @property CapabilityAnswer[] $capabilityAnswers
 * @property Company[] $companies
 * @property ModuleInstructor[] $moduleInstructors
 * @property ProgramEnrollment[] $programEnrollments
 * @property UnitReport[] $unitReports
 * @property UserProfile[] $userProfiles
 */
class Usercreate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'role', 'c_id'], 'required'],
            [['c_id'], 'integer'],
           
             [['username', 'email'], 'string', 'max' => 255],
             [['username'], 'unique','targetClass' => '\backend\models\Usercreate', 'message' => 'This Username has already been taken.'],
            [['email'], 'unique','targetClass' => '\backend\models\Usercreate', 'message' => 'This email address has already been taken.'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'c_id' => 'C ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwarenessAnswers()
    {
        return $this->hasMany(AwarenessAnswer::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapabilityAnswers()
    {
        return $this->hasMany(CapabilityAnswer::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['admin' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModuleInstructors()
    {
        return $this->hasMany(ModuleInstructor::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramEnrollments()
    {
        return $this->hasMany(ProgramEnrollment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitReports()
    {
        return $this->hasMany(UnitReport::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['user_id' => 'id']);
    }
	
	public function rolename($data) {
    
		if($data == 0)
			return 'User';
		else if($data == 1)
			return 'Accessor';
		else if($data == 2)
			return 'Company Admin';
		else if($data == 3)
			return 'Super Admin';

		}
}
