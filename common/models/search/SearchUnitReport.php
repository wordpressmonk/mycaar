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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_id','student_id'], 'integer'],
          //  [['firstname', 'lastname'], 'safe'],
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
    public function search($params,$unit_id)
    {
        $query = UnitReport::find()->where(['unit_id'=>$unit_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'unit_id' => $this->unit_id,
            'student_id' => $this->student_id,
         //   'location' => $this->location,
         //   'state' => $this->state,
        ]);

        return $dataProvider;
    }
}
