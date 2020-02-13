<?php

namespace app\models\forms;

use app\core\models\Datums;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\core\models\Files;
use yii\helpers\ArrayHelper;

/**
 * FilesSearch represents the model behind the search form of `app\core\models\Files`.
 */
class FilesSearch extends Model
{
    public $datum_id;
    public $file;

    public function datumList(): array
    {
        return ArrayHelper::map(Datums::find()->asArray()->all(), 'id', 'name');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datum_id'], 'integer'],
            [['file'], 'safe'],
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
        $query = Files::find()->with('datum');

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
            'datum_id' => $this->datum_id,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file]);

        return $dataProvider;
    }
}
