<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $company_id
 * @property string $name
 * @property string $about_us
 * @property string $logo
 * @property integer $admin
 *
 * @property User $admin0
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'admin'], 'required'],
            [['about_us', 'logo'], 'string'],
            [['admin'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['admin'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['admin' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'name' => 'Name',
            'about_us' => 'About Us',
            'logo' => 'Logo',
            'admin' => 'Admin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyAdmin()
    {
        return $this->hasOne(User::className(), ['id' => 'admin']);
    }
}
