<?php

namespace app\models\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\core\models\Datums;

/**
 * DatumsSearch represents the model behind the search form of `app\core\models\Datums`.
 */
class DatumsSearch extends Model
{
    public $date;

    public $name;
    public $code;
    public $id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'code', 'date'], 'safe'],
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
        $query = Datums::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->date = $params['date'] ? date('Y-m-d', strtotime($params['date'])): false ;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['>=', 'created_at', $this->date ? $this->date . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'created_at', $this->date ? $this->date . ' 23:59:59' : null])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
