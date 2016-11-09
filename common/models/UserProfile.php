<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $profile_id
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property integer $position
 * @property integer $location
 * @property integer $state
 *
 * @property User $user
 * @property Division $position0
 * @property Location $location0
 * @property State $state0
 */
class UserProfile extends \yii\db\ActiveRecord
{
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
            [['user_id'], 'required'],
            [['user_id', 'division', 'location', 'state'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['division'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['division' => 'division_id']],
            [['location'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location' => 'location_id']],
            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state' => 'state_id']],
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
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'division' => 'Division',
            'location' => 'Location',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition0()
    {
        return $this->hasOne(Division::className(), ['division_id' => 'position']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation0()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState0()
    {
        return $this->hasOne(State::className(), ['state_id' => 'state']);
    }
}
