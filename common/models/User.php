<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
 
class User extends ActiveRecord implements IdentityInterface
{
	public $fullname;
	public $role;
	public $password;

	
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'role'], 'required'],
			[['password'], 'required', 'except' => 'update_by_admin'],
            [['c_id'], 'integer'],           
            [['username', 'email'], 'string', 'max' => 255],
            [['username'], 'unique','targetClass' => '\common\models\User', 'message' => 'This Username has already been taken.'],
            [['email'], 'unique','targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            [['password_reset_token'], 'unique'],			
			['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }
	
	public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update_by_admin'] = ['role','c_id','username','email'];//Scenario Values Only Accepted
        return $scenarios;
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
            'password' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'c_id' => 'Company',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {		
		$user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);		
        $profile = UserProfile::find()->where(['user_id'=>$id])->one();	 			
		if($user && $profile)
		{
			$roles = Yii::$app->authManager->getRolesByUser($id);
			reset($roles);
			/* @var $role \yii\rbac\Role */
			$role = current($roles);
			$user->role = $role->name;
			
			$user->fullname= $profile->firstname." ".$profile->lastname;				
		/* 	switch ($user->role) {
				case 3:
					$role = "Superadmin" ;
					break;
				case 2:
					$role = "Companyadmin" ;
					break;
				case 1:
					$role = "Assessor" ;
					break;
				default:
					$role = "User";
			}	
			$user->role = $role; */
		}
		return $user;		
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
       // return static::findOne(['username' => $username, 'user_status' => self::STATUS_ACTIVE]);
     
        $val = static::find()->where("status = ".self::STATUS_ACTIVE." AND (username = '$username' OR email = '$username')")->one();		
		 return $val;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	/**
	 * Returns user role name according to RBAC
	 * @return string
	 */
	public function getRoleName()
	{
		$roles = Yii::$app->authManager->getRolesByUser($this->id);
		if (!$roles) {
			return null;
		}

		reset($roles);
		/* @var $role \yii\rbac\Role */
		$role = current($roles);

		return $role->name;
	}
	
	/**
     * Return All Users Details Depends Upon Role Name From DB
     */
    public function getUserAllByrole($rolename)
    {			 	
		$Roleusers = \Yii::$app->authManager->getUserIdsByRole($rolename);		
		$data = User::find()->where(['IN', 'id', $Roleusers])->all();
		return $data;
    }
	
	 
}