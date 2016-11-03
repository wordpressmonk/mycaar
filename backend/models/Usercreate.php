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
 * @property integer $user_status
 * @property string $created_at
 * @property string $updated_at
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
            [['username', 'email',], 'trim'],
            [['username', 'email',], 'required'],
			['email', 'email'],
            [['role', 'user_status'], 'integer'],
           
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
            'user_status' => 'User Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
