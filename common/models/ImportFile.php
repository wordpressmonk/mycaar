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
class ImportFile extends \yii\db\ActiveRecord
{
	
  public $upfile;
		

  	/**
     * Return All Users Details Depends Upon Role Name From DB
     */
  public function rules()
    {
        return [
            [['upfile'], 'required'],                  			
			[['upfile'], 'file','extensions' => 'xlsx,xls'],           
        ];
    }
	
	
   public function attributeLabels()
    {
        return [
            'upfile' => 'Upload File',          
        ];
    }
	
}
