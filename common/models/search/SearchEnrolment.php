<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Enrolment;


/**
 * SearchUser represents the model behind the search form about `common\models\Enrolment`.
 */
class SearchEnrolment extends Enrolment
{
    /**
     * @inheritdoc
     */
	 
    public function rules()
    {
        return [
               [['id','is_enrolled'], 'integer'],	
			   [['username'], 'string'],
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
		$query = Enrolment::find()->where(['c_id' =>Yii::$app->user->identity->c_id,'program_id'=>isset($params['program_id'])?$params['program_id']:'']);		
		
        // add conditions that should always apply here
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 0,
			],
        ]);
			
        $this->load($params);

        if (!$this->validate()) {        
            return $dataProvider;
        } 
		$query->andFilterWhere([
            'id' => $this->id,
            'is_enrolled' => $this->is_enrolled,          
           
        ]);
        
		$query->andFilterWhere(['like', 'username', $this->username]);	
		
        return $dataProvider;		
    }
}
