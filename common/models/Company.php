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
            [['about_us', 'logo'], 'required','on' => 'update_by_company_admin'],          
            [['about_us'], 'string'],
			[['logo'], 'file','extensions' => 'jpg,png', 'skipOnEmpty' => true],
            [['admin'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['admin'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['admin' => 'id']],
        ];
    }

	
	public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update_by_company_admin'] = ['name','about_us','logo']; //Scenario Values Only Accepted
        return $scenarios;
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
	

	/**
     * Upload the Company Logo On Particular Folder Structure
     */
	 
	public function uploadImage(){	
		if($this->validate()) {	
			$this->logo->saveAs('uploads/company_logo/'.$this->logo->baseName.'.'.$this->logo->extension);
			$this->logo = 'uploads/company_logo/'.$this->logo->baseName.'.'.$this->logo->extension;
			return true;	
		 }else {
			return false;
		}	 	
	}	
}
