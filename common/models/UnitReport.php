<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit_report".
 *
 * @property integer $report_id
 * @property integer $unit_id
 * @property integer $student_id
 * @property integer $awareness_progress
 * @property integer $capability_progress
 * @property string $updated_at
 *
 * @property User $student
 */
class UnitReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_id', 'student_id', 'awareness_progress'], 'required'],
            [['unit_id', 'student_id', 'awareness_progress', 'capability_progress'], 'integer'],
            [['updated_at'], 'safe'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['student_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'report_id' => 'Report ID',
            'unit_id' => 'Unit ID',
            'student_id' => 'Student ID',
            'awareness_progress' => 'Awareness Progress',
            'capability_progress' => 'Capability Progress',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'student_id']);
    }
	
	public function resetUser(){
			//delete awareness answers and cap answers also
			$a_answers = AwarenessAnswer::find()->joinWith(['awareness_question'])->where(['awareness_question.unit_id'=>$this->unit_id,'user_id'=>$this->student_id])->all();
			foreach($a_answers as $answer){
				$answer->delete();
			}
			$c_answers = CapabilityAnswer::find()->joinWith(['capability_question'])->where(['capability_question.unit_id'=>$this->unit_id,'user_id'=>$this->student_id])->all();
			foreach($c_answers as $answer){
				$answer->delete();
			}		
	}
}
