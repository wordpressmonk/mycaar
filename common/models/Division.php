<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property integer $division_id
 * @property integer $company_id
 * @property string $title
 * @property string $description
 *
 * @property UserProfile[] $userProfiles
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'title'], 'required'],
            [['company_id'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'division_id' => 'Division ID',
            'company_id' => 'Company Name',
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['division' => 'division_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['company_id' => 'company_id']);
    }
}
