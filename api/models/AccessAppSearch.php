<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\AccessApp;
use \common\helpers\BDataHelper;

/**
 * AccessAppSearch represents the model behind the search form about `api\models\AccessApp`.
 */
class AccessAppSearch extends AccessApp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appkey', 'appname', 'client_id', 'client_secret','eccount', 'created', 'modified'], 'safe'],
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
        if(BDataHelper::check_only_role()){
            $query =AccessApp::find()->where(['uid'=>BDataHelper::getCurrentUserId()]);
        }else{
            $query = AccessApp::find();;
        }


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
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'appkey', $this->appkey])
            ->andFilterWhere(['like', 'appname', $this->appname])
            ->andFilterWhere(['like', 'client_id', $this->client_id])
            ->andFilterWhere(['like', 'client_secret', $this->client_secret])
            ->andFilterWhere(['like', 'eccount', $this->eccount]);;

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
        $query = AccessApp::find();

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
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'appkey', $this->appkey])
            ->orFilterWhere(['like', 'appname', $this->appkey])
            ->orFilterWhere(['like', 'client_id', $this->appkey])
            ->orFilterWhere(['like', 'client_secret', $this->appkey])
            ->orFilterWhere(['like', 'eccount', $this->appkey]);;

        return $dataProvider;
    }
}
