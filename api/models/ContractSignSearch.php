<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\ContractSign;

/**
 * ContractSignSearch represents the model behind the search form about `api\models\ContractSign`.
 */
class ContractSignSearch extends ContractSign
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sign_id', 'contr_id', 'sign_userid', 'sign_time'], 'integer'],
            [['sign_name', 'sign_data', 'sign_sign', 'sign_file', 'mobile', 'code', 'email', 'openid', 'bindtime'], 'safe'],
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
        $query = ContractSign::find();

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
            'sign_id' => $this->sign_id,
            'contr_id' => $this->contr_id,
            'sign_userid' => $this->sign_userid,
            'sign_time' => $this->sign_time,
            'bindtime' => $this->bindtime,
        ]);

        $query->andFilterWhere(['like', 'sign_name', $this->sign_name])
            ->andFilterWhere(['like', 'sign_data', $this->sign_data])
            ->andFilterWhere(['like', 'sign_sign', $this->sign_sign])
            ->andFilterWhere(['like', 'sign_file', $this->sign_file])
            ->andFilterWhere(['like', 'sign_code', $this->sign_code])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'openid', $this->openid]);

        return $dataProvider;
    }
}
