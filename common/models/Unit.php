<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property integer $unit_id
 * @property integer $module_id
 * @property string $title
 * @property integer $status
 *
 * @property AwarenessQuestion[] $awarenessQuestions
 * @property CapabilityQuestion[] $capabilityQuestions
 * @property Module $module
 * @property UnitElement[] $unitElements
 * @property UnitReport[] $unitReports
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'title'], 'required'],
            [['module_id', 'status'], 'integer'],
            [['title'], 'string', 'max' => 1000],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => Module::className(), 'targetAttribute' => ['module_id' => 'module_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_id' => 'Unit ID',
            'module_id' => 'Module ID',
            'title' => 'Title',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwarenessQuestions()
    {
        return $this->hasMany(AwarenessQuestion::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapabilityQuestions()
    {
        return $this->hasMany(CapabilityQuestion::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Module::className(), ['module_id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitElements()
    {
        return $this->hasMany(UnitElement::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitReports()
    {
        return $this->hasMany(UnitReport::className(), ['unit_id' => 'unit_id']);
    }
}
