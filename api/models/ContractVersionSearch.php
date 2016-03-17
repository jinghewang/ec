<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\ContractVersion;

/**
 * ContractVersionSearch represents the model behind the search form about `api\models\ContractVersion`.
 */
class ContractVersionSearch extends ContractVersion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vercode', 'pcode', 'title', 'fileid', 'orgid', 'extra_data', 'createuser', 'createtime'], 'safe'],
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
        $query = ContractVersion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'vercode', $this->vercode])
            ->andFilterWhere(['like', 'pcode', $this->pcode])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'fileid', $this->fileid])
            ->andFilterWhere(['like', 'orgid', $this->orgid])
            ->andFilterWhere(['like', 'extra_data', $this->extra_data])
            ->andFilterWhere(['like', 'createuser', $this->createuser]);

        return $dataProvider;
    }
}
