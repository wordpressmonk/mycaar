<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Location;

/**
 * SearchLocation represents the model behind the search form about `common\models\Location`.
 */
class SearchLocation extends Location
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location_id', 'company_id'], 'integer'],
            [['name'], 'safe'],
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
			$query = Location::find();
		} else if(\Yii::$app->user->can('company_admin'))
		{			
			$query = Location::find()->where(['company_id' =>Yii::$app->user->identity->c_id]);
		}

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
            'location_id' => $this->location_id,
            'company_id' => $this->company_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
