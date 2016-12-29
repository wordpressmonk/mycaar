<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Module;
/**
 * Login form
 */
class CopyModule extends Model
{
   public $program_id;
   public $copy_program;
   public $copy_module;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'copy_program','copy_module'], 'required'],			
			[['copy_program'],'validateprogram'],
			[['copy_module'],'validatemodule'],
        ];
    }

	  public function attributeLabels()
    {
        return [
            'program_id' => 'Program Name',
            'copy_program' => 'Copy To Program Name',
            'copy_module' => 'Copy Of Module',
         
        ];
    }
	
	public function validateprogram($attribute,$params)
    {
		if($this->copy_program == $this->program_id)
		{
			$this->addError($attribute, 'Both Program are same cannot be copy');
			return false;   
		} 	
    }
	
	
	public function validatemodule($attribute,$params)
    {
		 $checkmodule = Module::findOne(['copy_id'=>$this->copy_module,'program_id'=>$this->copy_program]);
		if($checkmodule)
		{
			$this->addError($attribute, 'Already same module is copied to this program');
			return false;   
		} 	
    }
}
