<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_meta".
 *
 * @property integer $meta_id
 * @property integer $user_id
 * @property string $meta_key
 * @property string $meta_value
 */
class SiteMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	//public $siterightlogo;
	//public $siteleftlogo;
	
    public static function tableName()
    {
        return 'site_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [  
            [['meta_key'], 'string'],
            [['meta_value'], 'file','extensions' => 'jpg,png', 'skipOnEmpty' => true], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'meta_id' => 'Meta ID',
            'meta_value' => 'Meta Value',
            'meta_key' => 'Meta Key',
        ];
    }
	
	public function uploadImage(){	
		if($this->validate()) {	
			$this->meta_value->saveAs('uploads/side_logo/'.$this->meta_value->baseName.'.'.$this->meta_value->extension);
			$this->meta_value = 'uploads/side_logo/'.$this->meta_value->baseName.'.'.$this->meta_value->extension;
			return true;	
		 }else {
			return false;
		}	 	
	}
}
