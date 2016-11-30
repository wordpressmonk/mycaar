<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UnitReport;

/**
 * SearchUserProfile represents the model behind the search form about `common\models\UserProfile`.
 */
class SearchUnitReport extends UnitReport
{
    public $module_id;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          //  [['unit_id'], 'integer'],
            [['cap_done_by','student_id','unit_id','module_id'], 'string'],
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
        $query = UnitReport::find()->where(['not',['cap_done_by'=>NULL]]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$query->joinWith(['unit as u']);
		$query->joinWith(['unit.module as m']);
		$query->joinWith(['user_profile as profile']);
		$query->joinWith(['assessor as assr']);
		$query->joinWith(['assessor.user']);
		//$query->joinWith(['program']);
		$query->andFilterWhere(['user.c_id'=>\Yii::$app->user->identity->c_id]);
		
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		$query->andFilterWhere(['like', 'assr.firstname', $this->cap_done_by])
			->orFilterWhere(['like', 'assr.lastname', $this->cap_done_by]);

		$query->andFilterWhere(['like', 'profile.firstname', $this->student_id])
			->orFilterWhere(['like', 'profile.lastname', $this->student_id]);				

		$query->andFilterWhere(['like', 'u.title', $this->unit_id]);
		$query->andFilterWhere(['like', 'm.title', $this->module_id]);
		//}

        return $dataProvider;
    }

	public function searchCustom($param,$unit_id=null)
	{
		if($unit_id)
			$query = UnitReport::find()->where(['unit_id'=>$unit_id]);
		else $query = UnitReport::find();
        // add conditions that should always apply here
		//print_R($param);die;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$query->joinWith(['user_profile.user']);
		//$query->joinWith(['program']);
		$query->andFilterWhere(['user.c_id'=>\Yii::$app->user->identity->c_id]);
			if(isset($param['user_id']) && $param['user_id'] !='')
				$query->andFilterWhere(['user_id'=>$param['user_id']]);					
 			if(isset($param['state']) && $param['state'] !='')
				$query->andFilterWhere(['state'=>$param['state']]);
			if(isset($param['role']) && $param['role'] !='')
				$query->andFilterWhere(['role'=>$param['role']]);
			if(isset($param['location']) && $param['location'] !='')
				$query->andFilterWhere(['location'=>$param['location']]);
			if(isset($param['division']) && $param['division'] !='')
				$query->andFilterWhere(['division'=>$param['division']]); 
			if(isset($param['firstname']) && $param['firstname'] !='')
				$query->andFilterWhere(['like', 'firstname',$param['firstname']]);
			if(isset($param['lastname']) && $param['lastname'] !='')
				$query->andFilterWhere(['like', 'lastname', $param['lastname']]);	
		return $dataProvider;
	}
}
