<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
/**
 * SearchProgramEnrollment represents the model behind the search form about `common\models\ProgramEnrollment`.
 */
class SearchProgramEnrollment extends User
{
    /**
     * @inheritdoc
     */
	 

	public $enrollcheck;
	
    public function rules()
    {
        return [
            [['id','enrollcheck'], 'integer'],

			[['username','fullname'], 'safe'],	
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		$program_id = '';
		$username = '';
		$enroll = '';
		$fullname = '';
		
		if(isset($params['program_id']) && !empty($params['program_id']))
			$program_id = $params['program_id'];
		
		if(isset($params['SearchProgramEnrollment']['username']) && !empty($params['SearchProgramEnrollment']['username']))
			$username = $params['SearchProgramEnrollment']['username'];
		
		if(isset($params['SearchProgramEnrollment']['enrollcheck']) && ($params['SearchProgramEnrollment']['enrollcheck'] != ''))
			$enroll = "where tmp.is_enrolled = ".$params['SearchProgramEnrollment']['enrollcheck'];
		
		if(isset($params['SearchProgramEnrollment']['fullname']) && !empty($params['SearchProgramEnrollment']['fullname']))
		{
		  if(isset($params['SearchProgramEnrollment']['enrollcheck']) && ($params['SearchProgramEnrollment']['enrollcheck'] != ''))
			 $fullname = ' and tmp.fullname LIKE "%'.$params['SearchProgramEnrollment']['fullname'].'%"';
		 else
			$fullname = 'where tmp.fullname LIKE "%'.$params['SearchProgramEnrollment']['fullname'].'%"';	
			
		}
			
			$tmpwhere = $enroll.$fullname;
		
		$dataProvider = new SqlDataProvider([
			'sql' => 'select * from ( select 
	`p`.`program_id` AS `program_id`,
	`u`.`id` AS `id`,
	`u`.`username` AS `username`,
	 CONCAT(`up`.firstname," ",`up`.lastname) AS `fullname`,
	`u`.`c_id` AS `c_id`,
	if((`pe`.`e_id` is not null),1,0) AS `is_enrolled` 
from 
	((`program` `p` 
	join 
		`user` `u` FORCE INDEX (`c_id`) on((`u`.`c_id` = `p`.`company_id` and u.`c_id` = '.Yii::$app->user->identity->c_id.' and p.program_id = '.$program_id.' and  (u.username like "%'.$username.'%")
		))) 
   left join 
		`user_profile` `up` on ((`up`.`user_id` = `u`.`id`))	
   left join 
		`program_enrollment` `pe` on(((`pe`.`program_id` = `p`.`program_id`) and (`pe`.`user_id` = `u`.`id`)
		
		))) ) as tmp '.$tmpwhere.' order by tmp.fullname' ,
	

			'pagination' => [
				'pageSize' => 0,
				],
			]);
			
			
		if (!($this->load($params) && $this->validate())) {		
			return $dataProvider;
		}
		
        return $dataProvider;
    }
}
