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
               [['id'], 'integer'],					
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
		
		
		 if(\Yii::$app->user->can('company manage')) 
		{
			 $query = Enrolment::find();
		} else if(\Yii::$app->user->can('company_admin'))
		{			
			 $query = Enrolment::find()->where(['c_id' =>Yii::$app->user->identity->c_id]);
		}
        // add conditions that should always apply here
		
	
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 10,
				],
        ]);
			
        $this->load($params);

	
         if (!$this->validate()) {        
            return $dataProvider;
        } 

		
        // grid filtering conditions
        $query->andFilterWhere([
            'enrolled' => $this->enrolled,
           
            
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);
		
        return $dataProvider;
		
    }
}
