<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use common\models\ProgramEnrollment;
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
            [['id'], 'integer'],
			[['username'], 'safe'],	
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

		if(isset($params['program_id']))
			$program_id = $params['program_id'];
		else
			$program_id = '';
		
		if(isset($params['SearchProgramEnrollment']['username']))
			$username = $params['SearchProgramEnrollment']['username'];
		else 
			$username = '';
		
		/* $command = (new \yii\db\Query())
    ->select(['id', 'email'])
    ->from('user')
    ->where(['last_name' => 'Smith'])
    ->limit(10)
    ->createCommand();
	
	echo $command->sql;
	exit; */
		
 /* 		$query = (new Query())->select(['`program`.`program_id` as `program_id`','`user`.`id` AS `id`','`user`.`username` AS `username`','`user`.`c_id` AS `c_id`','if((`program_enrollment`.`e_id` is not null),1,0) AS `is_enrolled` '])
								->from('user')
								->join('JOIN','program', 'program.company_id = user.c_id and program.program_id = 1')
								->join('left join','program_enrollment', 'program_enrollment.program_id = program.program_id and `program_enrollment`.`user_id` = `user`.`id` and user.c_id =3')
								->createCommand();
								 */
		//$rows = $command->queryAll();
/* 					echo "<pre>";
print_r($query);
exit;					
			echo $query->sql;
	exit; */
		
		/* $query = (new Query())->from('user u, program_enrollment p')->where('p.user_id = u.id and u.c_id => 3 and p.program_id = '.isset($params['program_id'])?$params['program_id']:'' );  */
		

		/* $query = (new Query())->from('user')->join('JOIN', 'program_enrollment', 'program_enrollment.user_id = user.id and user.c_id => '.Yii::$app->user->identity->c_id.' and program_enrollment.program_id = '.isset($params['program_id'])?$params['program_id']:'' );  */

       // $query = User::find()->select(['id','username'])->where(['c_id'=>Yii::$app->user->identity->c_id]);

			
        // add conditions that should always apply here
		
		
/* 		$query = [
    ['id' => 1, 'username' => 'name 1',],
    ['id' => 2, 'username' => 'name 2', ],

    ['id' => 100, 'username' => 'name 100',],
]; */

/* $dataProvider = new ArrayDataProvider([
    'allModels' => $query,
 
    'sort' => [
        'attributes' => ['id', 'username'],
    ],
]);
 */
      /*    $dataProvider = new ActiveDataProvider([
		
            'query' => $query,
			
			
			'pagination' => [
				'pageSize' => 0,
			],
        ]);  */
		
		$dataProvider = new SqlDataProvider([
			'sql' => 'select 
	`p`.`program_id` AS `program_id`,
	`u`.`id` AS `id`,
	`u`.`username` AS `username`,
	`u`.`c_id` AS `c_id`,
	if((`pe`.`e_id` is not null),1,0) AS `is_enrolled` 
from 
	((`mycaar_lms`.`program` `p` 
	join 
		`mycaar_lms`.`user` `u` FORCE INDEX (`c_id`) on((`u`.`c_id` = `p`.`company_id` and u.`c_id` = '.Yii::$app->user->identity->c_id.' and p.program_id = '.$program_id.' and  (u.username like "%'.$username.'%")
		))) 
   left join 
		`mycaar_lms`.`program_enrollment` `pe` on(((`pe`.`program_id` = `p`.`program_id`) and (`pe`.`user_id` = `u`.`id`)
		
		)))',
			'params' => [':enrollcheck' => 1],

			'pagination' => [
				'pageSize' => 20,
				],
			]);


/* 
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
		
		
	
            return $dataProvider;
        } */
	
	
		if (!($this->load($params) && $this->validate())) {
		
			return $dataProvider;
		}

	 	/* if(isset($params['enrollcheck']) && ($params['enrollcheck'] == 0))
		{		
			
		 $query = $query->join('JOIN', 'program_enrollment', 'program_enrollment.user_id = user.id and program_enrollment.program_id = '.isset($params['program_id'])?$params['program_id']:'' );
		}  */ 
		
        // grid filtering conditions
        /*  $query->andFilterWhere([
            'id' => $this->id,
        ]);  */

		//$query->andFilterWhere(['like', 'username', $this->username]); 
		
		
		
        return $dataProvider;
    }
}
