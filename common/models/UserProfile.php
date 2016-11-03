<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $profile_id
 * @property integer $user_id
 * @property integer $profile_status
 * @property string $firstname
 * @property string $lastname
 * @property string $profile_url
 * @property string $profile_photo
 * @property string $short_description
 * @property string $display_url
 * @property string $display_email
 * @property string $profile_bg_image
 * @property string $bg_properties
 * @property integer $profile_privacy
 * @property string $temp_image
 *
 * @property User $user
 * @property Privacy $profilePrivacy
 */
class UserProfile extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_url', 'short_description'], 'string'],
            [['fullname', 'profile_photo', 'temp_image','display_email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['profile_url'],'url'],
			[['bio'],  'string', 'max' => 500],
           // [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg' ]
           // ['verify_password', 'compare', 'compareAttribute' => 'password'],
          //  ['verify_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'user_id' => 'User ID',
            'profile_status' => 'Profile Status',
            'fullname' => 'Name',
            'profile_url' => 'Profile Url',
            'profile_photo' => 'Profile Photo',
			'bio' => 'Bio',
            'short_description' => 'Short Description',
            'profile_url' => 'Profile Url',
            'display_email' => 'Display Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

/* 	public function getFullName()
	{
		return $this->firstname.' '.$this->lastname;
	} */
	/**
	 * @return mixed
	 */
	public function saveProfile(){
        if (!$this->validate()) {
            return null;
        }
		//upload pic here
	
	}
}