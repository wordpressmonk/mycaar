<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property integer $division_id
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
            [['division_id', 'title'], 'required'],
            [['division_id'], 'integer'],
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
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['position' => 'division_id']);
    }
}
