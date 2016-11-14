<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $location_id
 * @property integer $company_id
 * @property string $name
 *
 * @property UserProfile[] $userProfiles
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'name'], 'required'],
            [['company_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'location_id' => 'Location ID',
            'company_id' => 'Company Name',
            'name' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['location' => 'location_id']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['company_id' => 'company_id']);
    }
}
