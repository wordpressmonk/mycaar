<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "program".
 *
 * @property integer $program_id
 * @property string $title
 * @property integer $company_id
 * @property string $description
 *
 * @property Module[] $modules
 * @property ProgramEnrollment[] $programEnrollments
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'company_id'], 'required'],
            [['title', 'description'], 'string'],
            [['company_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'program_id' => 'Program ID',
            'title' => 'Title',
            'company_id' => 'Company',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramEnrollments()
    {
        return $this->hasMany(ProgramEnrollment::className(), ['program_id' => 'program_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['company_id' => 'company_id']);
    }
}
