<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Request;

/**
 * RequestSearch represents the model behind the search form of `app\models\Request`.
 */
class RequestSearch extends Request
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'updated_at', 'option'], 'integer'],
            [['name', 'message', 'image', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Request::find();

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

        if (!empty($this->created_at) ) {
            // date to search        
            $date = \DateTime::createFromFormat('Y-m-d', $this->created_at);
            $date->setTime(0, 0, 0);

            // set lowest date value
            $unixDateStart = $date->getTimeStamp();

            // add 1 day and subtract 1 second
            $date->add(new \DateInterval('P1D'));
            $date->sub(new \DateInterval('PT1S'));

            // set highest date value
            $unixDateEnd = $date->getTimeStamp();

            $query->andFilterWhere([ 'between' , 'created_at' , $unixDateStart , $unixDateEnd ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'option' => $this->option,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
