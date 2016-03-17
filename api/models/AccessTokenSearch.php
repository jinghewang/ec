<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\AccessToken;

/**
 * AccessTokenSearch represents the model behind the search form about `app\models\AccessToken`.
 */
class AccessTokenSearch extends AccessToken
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tokenid', 'clientid', 'appkey', 'orgid', 'uid', 'createtime'], 'safe'],
            [['validity'], 'integer'],
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
        $query = AccessToken::find();

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
            'validity' => $this->validity,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'tokenid', $this->tokenid])
            ->andFilterWhere(['like', 'clientid', $this->clientid])
            ->andFilterWhere(['like', 'appkey', $this->appkey])
            ->andFilterWhere(['like', 'orgid', $this->orgid])
            ->andFilterWhere(['like', 'uid', $this->uid]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchKeyword($params)
    {
        $query = AccessToken::find();

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
            'validity' => $this->validity,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'tokenid', $this->tokenid])
            ->orFilterWhere(['like', 'clientid', $this->tokenid])
            ->orFilterWhere(['like', 'appkey', $this->tokenid])
            ->orFilterWhere(['like', 'orgid', $this->tokenid])
            ->orFilterWhere(['like', 'uid', $this->tokenid]);

        return $dataProvider;
    }
}
