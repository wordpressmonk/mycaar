<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property integer $module_id
 * @property integer $program_id
 * @property string $title
 * @property string $short_description
 * @property string $featured_video_url
 * @property string $detailed_description
 * @property integer $status
 *
 * @property Program $program
 * @property ModuleInstructor[] $moduleInstructors
 * @property Unit[] $units
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'title'], 'required'],
            [['program_id', 'status'], 'integer'],
            [['short_description', 'detailed_description'], 'string'],
			[['featured_image'], 'file','extensions' => 'jpg,png', 'skipOnEmpty' => true],
			[['featured_video_url'], 'file','extensions' => 'mp4', 'skipOnEmpty' => true],
            [['title'], 'string', 'max' => 1000],
			[['language'], 'string', 'max' => 200],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'program_id']],
			[['course_start_date','course_end_date','enrl_start_date','enrl_end_date','is_course_open_anytime','is_enrlmnt_open_anytime'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'module_id' => 'Module ID',
            'program_id' => 'Program ID',
            'title' => 'Course Name',
            'short_description' => 'Short Description',
            'featured_video_url' => 'Featured Video',
            'detailed_description' => 'Detailed Description',
            'status' => 'Status',
			'language'=> 'Course Language',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModuleInstructors()
    {
        return $this->hasMany(ModuleInstructor::className(), ['module_id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['module_id' => 'module_id']);
    }
	
	public function uploadImage(){
		if($this->validate()) {
			$this->featured_image->saveAs('uploads/' . $this->featured_image->baseName . '.' .$this->featured_image->extension);
			$this->featured_image = 'uploads/'.$this->featured_image->baseName.'.'.$this->featured_image->extension;
			return true;
		}else
			return false;
	}
	public function uploadVideo(){
		if($this->validate()) {
			$this->featured_video_url->saveAs('uploads/' . $this->featured_video_url->baseName . '.' .$this->featured_video_url->extension);
			$this->featured_video_url = 'uploads/'.$this->featured_video_url->baseName.'.'.$this->featured_video_url->extension;
			return true;
		}else
			return false;
	}
}
